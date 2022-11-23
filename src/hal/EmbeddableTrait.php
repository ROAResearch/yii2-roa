<?php

namespace roaresearch\yii2\roa\hal;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\helpers\ArrayHelper;
use yii\web\Link;
use yii\web\Linkable;

/**
 * Interface to get a the information of a file associated to a model.
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
trait EmbeddableTrait
{
    use ArrayableTrait {
        extractFieldsFor as baseExtractFieldsFor;
    }

    /**
     * @inheritdoc
     */
    public abstract function fields();

    /**
     * @inheritdoc
     */
    public abstract function extraFields();

    /**
     * @inheritdoc
     */
    public function toArray(
        array $fields = [],
        array $expand = [],
        $recursive = true
    ) {
        $data = [];
        foreach ($this->resolveFieldList($fields) as $field => $definition) {
            $data[$field] = $this->processField($field, $definition);
        }

        foreach ($this->resolveExpandList($expand) as $field => $definition) {
            $attribute = $this->processField($field, $definition);

            if ($recursive) {
                $nestedFields = $this->extractFieldsFor($fields, $field);
                $nestedExpand = $this->extractFieldsFor($expand, $field);
                if ($attribute instanceof Arrayable) {
                    $attribute = $attribute->toArray(
                        $nestedFields,
                        $nestedExpand
                    );
                } elseif (is_array($attribute)) {
                    $attribute = array_map(
                        function ($item) use ($nestedFields, $nestedExpand) {
                            if ($item instanceof Arrayable) {
                                return $item->toArray(
                                    $nestedFields,
                                    $nestedExpand
                                );
                            }
                            return $item;
                        },
                        $attribute
                    );
                }
            }

            if ($envelope = $this->getExpandEnvelope()) {
                $data[$envelope][$field] = $attribute;
            } else {
                $data[$field] = $attribute;
            }
        }

        if ($this instanceof Linkable && !in_array('!_links', $fields)) {
            $data['_links'] = Link::serialize($this->getLinks());
        }

        return $recursive ? ArrayHelper::toArray($data) : $data;
    }

    /**
     * @return string property which will contain all the expanded parameters.
     */
    public function getExpandEnvelope(): string
    {
        return Embeddable::EMBEDDED_PROPERTY;
    }
    
    
    /**
     * Extract nested fields from a fields collection for a given root field
     * Nested fields are separated with dots (.). e.g: "item.id"
     * The previous example would extract "id".
     *
     * Since 4.0.1 the field "!_links" is supported and handled specialy in
     * the sense that if its present on root it will be inherited to any
     * nested item.
     *
     * @param array $fields The fields requested for extraction
     * @param string $rootField The root field for which we want to extract the nested fields
     * @return array nested fields extracted for the given field
     */
    protected function extractFieldsFor(array $fields, $rootField)
    {
        return $this->baseExtractFieldsFor($fields, $rootField)
            + (in_array('!_links', $fields) ? ['!_links'] : []);
    }

    /**
     * Determines which fields can be returned by [[toArray()]].
     * This method will first extract the root fields from the given fields.
     * Then it will check the requested root fields against those declared in
     * [[fields()]] to determine which fields can be returned.
     *
     * @param array $fields the fields being requested for exporting
     * @return array the list of fields to be exported. The array keys are the
     * field names, and the array values are the corresponding object property
     * names or PHP callables returning the field values.
     */
    protected function resolveFieldList($fields): array
    {
        $fields = $this->extractRootFields($fields);
        $result = [];

        foreach ($this->fields() as $field => $definition) {
            if (is_int($field)) {
                $field = $definition;
            }

            if (empty($fields) || in_array($field, $fields, true)) {
                $result[$field] = $definition;
            }
        }

        return $result;
    }

    /**
     * Determines which expand fields can be returned by [[toArray()]].
     * This method will first extract the root fields from the given expand.
     * Then it will check the requested root fields against those declared in
     * [[extraFields()]] to determine which fields can be returned.
     *
     * @param array $expand the expand fields being requested for exporting
     * @return array the list of fields to be exported. The array keys are the
     * field names, and the array values are the corresponding object property
     * names or PHP callables returning the field values.
     */
    protected function resolveExpandList($expand): array
    {
        if (empty($expand)) {
            return [];
        }

        $fields = $this->extractRootFields($expand);
        $result = [];

        foreach ($this->extraFields() as $field => $definition) {
                if (is_int($field)) {
                    $field = $definition;
                }

                if (in_array($field, $fields, true)) {
                    $result[$field] = $definition;
                }
        }

        return $result;
    }

    /**
     * @param string $field name of the field to be resolved.
     * @param string|callable $definition the field definition, it its an string
     * it will be used as a property name, or a callable with signature.
     *
     * ```php
     * function ($model, string $field)
     * ```
     * @return mixed data obtained from the model.
     */
    protected function processField($field, $definition)
    {
        return is_string($definition)
            ? $this->$definition
            : $definition($this, $field);
    }
}

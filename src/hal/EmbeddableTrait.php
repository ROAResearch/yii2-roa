<?php

namespace roaresearch\yii2\roa\hal;

use Closure;
use roaresearch\yii2\roa\ArrayHelper;
use yii\{base\Arrayable, web\Link, web\Linkable};

/**
 * Interface to get a the information of a file associated to a model.
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
trait EmbeddableTrait
{
    /**
     * @inheritdoc
     */
    public function toArray(
        array $fields = [],
        array $expand = [],
        $recursive = true
    ) {
        $processField = Closure::fromCallable([$this, 'processField']);

        $data = ArrayHelper::normalizeFilter(
            $this->fields() + ($this instanceof Linkable
                ? ['_links' => fn () => Link::serialize($this->getLinks())]
                : []
            ),
            $fields,
            $processField
        );

        $expanded = ArrayHelper::normalizeStrict(
            $this->extraFields(),
            $expand,
            $recursive
                ? fn ($field, $definition) => $this->processRecursiveField(
                    $field,
                    $definition,
                    $fields,
                    $expand,
                )
                : $processField 
        );

        if (($envelope = $this->getExpandEnvelope()) && !empty($expanded)) {
            $data[$envelope] = $expanded;
        } else {
            $data += $expanded;
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
     * @param string $field name of the field to be resolved.
     * @param string|callable $definition the field definition, it its an string
     * it will be used as a property name, or a callable with signature.
     *
     * ```php
     * function ($model, string $field)
     * ```
     * @return mixed data obtained from the model.
     */
    protected function processField(string $field, $definition)
    {
        return is_string($definition)
            ? $this->$definition
            : $definition($this, $field);
    }

    /**
     * @param string $field name of the field to be resolved.
     * @param string|callable $definition the field definition, it its an string
     * it will be used as a property name, or a callable with signature.
     *
     * ```php
     * function ($model, string $field)
     * ```
     * @param array $fields
     * @param array $expand
     * @return mixed data obtained from the model.
     */
    protected function processRecursiveField(
        string $field,
        $definition,
        array $fields,
        array $expand
    ) {
        // notice that if this function is callled it means $expand is not empty
        // and the call is recursive
        $attribute = $this->processField($field, $definition);
        $nestedFields = ArrayHelper::fieldsFor($fields, $field);
        $nestedExpand = ArrayHelper::fieldsFor($expand, $field);

        if ($attribute instanceof Arrayable) {
            return $attribute->toArray(
                $nestedFields,
                $nestedExpand
            );
        } elseif (is_array($attribute)) {
            return array_map(
                fn ($item) => $item instanceof Arrayable 
                    ? $item->toArray(
                        $nestedFields,
                        $nestedExpand
                    )
                    : $item,
                $attribute
            );
        }

        return $attribute;
    }
}

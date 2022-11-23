<?php

namespace roaresearch\yii2\roa\behaviors;

use roaresearch\yii2\roa\hal\ARContract;
use yii\{
    base\Action,
    base\InvalidConfigException,
    db\BaseActiveRecord,
    helpers\Url,
    web\NotFoundHttpException,
};

/**
 * Behavior to handle slug componentes linked as parent-child relations.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 * @author Luis (Berkant) Campos <lcampos@artificesweb.com>
 * @author Alejandro (Seether69) Márquez <amarquez@solmipro.com>
 */
class Slug extends \yii\base\Behavior
{
    /**
     * @var ?string name of the parent relation of the `$owner`
     */
    public ?string $parentSlugRelation = null;

    /**
     * @var string name of the resource
     */
    public string $resourceName;

    /**
     * @var array name of the identifier attribute
     */
    public array $idAttributes = ['id'];

    /**
     * @var string separator to create the route for resources with multiple id
     * attributes.
     */
    public string $idAttributeSeparator = '/';

    /**
     * @var string parentNotFoundMessage for not found exception when the parent
     * slug was not found
     */
    public string $parentNotFoundMessage = '"{resourceName}" not found';

    /**
     * @var ?ARContract parent record.
     */
    protected ?ARContract $parentSlug = null;

    /**
     * @var string url to resource
     */
    protected string $resourceLink;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->resourceName ?: throw new InvalidConfigException(
            $this::class . '::$resourceName must be defined.'
        );
    }

    /**
     * Ensures the parent record is attached to the behavior.
     *
     * @param ARContract $owner
     * @param bool $force whether to force finding the slug parent record
     * when `$parentSlugRelation` is defined
     */
    private function ensureSlug(ARContract $owner, bool $force = false)
    {
        if (null === $this->parentSlugRelation) {
            $this->resourceLink = $this->defaultResourceLink();
        } elseif (
            $force
            || $owner->isRelationPopulated($this->parentSlugRelation)
        ) {
            $this->populateSlugParent($owner);
        }
    }

    /**
     * @return string default resource link used at bottom level resources.
     */
    protected function defaultResourceLink(): string
    {
        return Url::to([$this->resourceName . '/'], true);
    }

    /**
     * This populates the slug to the parentSlug
     * @param BaseActiveRecord $owner
     */
    private function populateSlugParent(ARContract $owner)
    {
        $this->parentSlug = $owner->{$this->parentSlugRelation}
            ?: throw new NotFoundHttpException(
                strtr(
                    $this->parentNotFoundMessage,
                    ['{resourceName}' => $this->parentSlugRelation]
                )
            );

        $this->resourceLink = $this->parentSlug->getSelfLink()
            . '/' . $this->resourceName;
    }

    /**
     * @return string value of the owner's identifier
     */
    public function getResourceRecordId(): string
    {
        $attributeValues = [];
        foreach ($this->idAttributes as $attribute) {
            $attributeValues[] = $this->owner->$attribute;
        }

        return implode($this->idAttributeSeparator, $attributeValues);
    }

    /**
     * @return string HTTP Url to the resource list
     */
    public function getResourceLink(): string
    {
        $this->ensureSlug($this->owner, true);

        return $this->resourceLink;
    }

    /**
     * @return string HTTP Url to self resource
     */
    public function getSelfLink(): string
    {
        $resourceRecordId = $this->getResourceRecordId();
        $resourceLink = $this->getResourceLink();

        return $resourceRecordId
            ? "$resourceLink/$resourceRecordId"
            : $resourceLink;
    }

    /**
     * @return array link to self resource and all the acumulated parent's links
     */
    public function getSlugLinks(): array
    {
        $this->ensureSlug($this->owner, true);
        $selfLinks = [
            'self' => $this->getSelfLink(),
            $this->resourceName . '_collection' => $this->resourceLink,
        ];

        if (null === $this->parentSlug) {
            return $selfLinks;
        }

        $parentLinks = $this->parentSlug->getSlugLinks();
        $parentLinks[$this->parentSlugRelation . '_record']
            = $parentLinks['self'];
        unset($parentLinks['self']);

        // preserve order
        return array_merge($selfLinks, $parentLinks);
    }

    /**
     * Determines if the logged user has permission to access a resource
     * record or any of its chidren resources.
     *
     * When extending this method make sure to call the parent at the end.
     *
     * ```php
     * // custom logic
     * parent::checkAccess($params, $action);
     * ```
     *
     * @param  array $params
     * @param ?Action $action
     * @throws \yii\web\HttpException
     */
    public function checkAccess(array $params, ?Action $action = null): void
    {
        $this->ensureSlug($this->owner, true);
        $this->parentSlug?->checkAccess($params, $action);
    }
}

<?php

namespace roaresearch\yii2\roa\hal;

use roaresearch\yii2\roa\behaviors\{Curies, Slug};
use yii\base\Action;

/**
 * Trait which gives the basic support for HAL contracts.
 */
trait ContractTrait
{
    use EmbeddableTrait;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            $this->getSlugBehaviorId() => ['class' => Slug::class]
                + $this->slugBehaviorConfig(),
            $this->getCuriesBehaviorId() => ['class' => Curies::class]
                + $this->curiesBehaviorConfig(),
        ]);
    }

    public function checkAccess(
        array $params = [],
        ?Action $action = null
    ): void {
        $this->getSlugBehavior()->checkAccess($params, $action);
    }

    abstract protected function slugBehaviorConfig(): array;

    protected function curiesBehaviorConfig(): array
    {
        return [];
    }

    protected function getSlugBehaviorId(): string
    {
        return 'slug';
    }

    protected function getCuriesBehaviorId(): string
    {
        return 'curies';
    }

    public function getSlugBehavior(): Slug
    {
        return $this->getBehavior($this->getSlugBehaviorId());
    }

    public function getCuriesBehavior(): Curies
    {
        return $this->getBehavior($this->getCuriesBehaviorId());
    }

    public function getSelfLink(): string
    {
        return $this->getSlugBehavior()->getSelfLink();
    }

    public function getLinks()
    {
        return $this->getSlugBehavior()->getSlugLinks()
            + $this->getCuriesBehavior()->getCuriesLinks();
    }
}

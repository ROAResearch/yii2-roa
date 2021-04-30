<?php

namespace roaresearch\yii2\roa\hal;

use roaresearch\yii2\roa\behaviors\{Curies, Slug};
use yii\{base\Action, di\Instance};

/**
 * Trait which gives the basic support for HAL contracts.
 */
trait ContractTrait
{
    use EmbeddableTrait;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            $this->getSlugBehaviorId() => Instance::ensure(
                $this->slugBehaviorConfig(),
                Slug::class
            ),
            $this->getCuriesBehaviorId() => Instance::ensure(
                $this->curiesBehaviorConfig(),
                Curies::class
            ),
        ]);
    }

    public function checkAccess(
        array $params = [],
        ?Action $action = null
    ): void {
        $this->getSlugBehavior()->checkAccess($params, $action);
    }

    abstract protected function slugBehaviorConfig(): array|Slug;

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

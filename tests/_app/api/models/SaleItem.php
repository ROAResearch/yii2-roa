<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\{ARContract, ContractTrait};

/**
 * ROA contract to handle shop sale item records.
 */
class SaleItem extends \app\models\SaleItem implements ARContract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected string $saleClass = Sale::class;

    /**
     * @inheritdoc
     */
    protected string $itemClass = Item::class;

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'sale',
            'item'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function slugBehaviorConfig(): array
    {
        return [
            'resourceName' => 'item',
            'parentSlugRelation' => 'sale',
        ];
    }
}

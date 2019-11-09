<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\{Contract, ContractTrait};

/**
 * ROA contract to handle shop sale item records.
 */
class SaleItem extends \app\models\SaleItem implements Contract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected $saleClass = Sale::class;

    /**
     * @inheritdoc
     */
    protected $itemClass = Item::class;

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

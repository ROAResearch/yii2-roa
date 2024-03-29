<?php

namespace app\api\resources;

use app\api\models\Sale;
use app\models\SoftDeleteQuery;
use roaresearch\yii2\roa\controllers\RestoreResource;
use yii\db\ActiveQuery;

/**
 * Resource to
 */
class SaleRestoreResource extends RestoreResource
{
    /**
     * @inheritdoc
     */
    public string $idAttribute = 'sale.id';

    /**
     * @inheritdoc
     */
    public $modelClass = Sale::class;

    /**
     * @inheritdoc
     */
    public array $filterParams = ['employee_id', 'shop_id'];

    /**
     * @inheritdoc
     */
    protected function baseQuery(): ActiveQuery
    {
        return parent::baseQuery()
            ->andFilterDeleted('sale', true)
            ->innerJoinWith([
                'employee' => function (SoftDeleteQuery $query) {
                    // only active employees
                    $query->andFilterDeleted('employee');
                },
                'employee.shop' => function (SoftDeleteQuery $query) {
                    // only active shops
                    $query->andFilterDeleted('shop');
                },
            ]);
    }
}



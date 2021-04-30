<?php

namespace app\api\resources;

use app\api\models\Employee;
use app\models\SoftDeleteQuery;
use roaresearch\yii2\roa\controllers\RestoreResource;
use yii\db\ActiveQuery;

/**
 * Resource to 
 */
class EmployeeRestoreResource extends RestoreResource
{
    /**
     * @inheritdoc
     */
    public string $idAttribute = 'e.id';

    /**
     * @inheritdoc
     */
    public $modelClass = Employee::class;

    /**
     * @inheritdoc
     */
    public array $filterParams = ['shop_id'];

    /**
     * @inheritdoc
     */
    protected function baseQuery(): ActiveQuery
    {
        return parent::baseQuery()
            ->andFilterDeleted('e', false)
            ->innerJoinWith([
                'shop' => function (SoftDeleteQuery $query) {
                    // only find if shop is not deleted.
                    $query->andFilterDeleted('s');
                },
            ]);
    }
}

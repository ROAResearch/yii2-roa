<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\{Contract, ContractTrait};

/**
 * ROA contract to handle shop sale records.
 */
class Sale extends \app\models\Sale implements Contract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected $employeeClass = Employee::class;

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'employee'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function slugBehaviorConfig(): array
    {
        return [
            'resourceName' => 'sale',
            'parentSlugRelation' => 'employee',
        ];
    }
}

<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\{Contract, ContractTrait};

/**
 * ROA contract to handle shop employee records.
 */
class Employee extends \app\models\Employee implements Contract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected $shopClass = Shop::class;

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['shop'];
    }

    /**
     * @inheritdoc
     */
    protected function slugBehaviorConfig(): array
    {
        return [
            'resourceName' => 'employee',
            'parentSlugRelation' => 'shop',
        ];
    }
}

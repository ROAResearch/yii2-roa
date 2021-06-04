<?php

namespace app\api\models;

use roaresearch\yii2\roa\hal\{ARContract, ContractTrait};

/**
 * ROA contract to handle shop employee records.
 */
class Employee extends \app\models\Employee implements ARContract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected string $shopClass = Shop::class;

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

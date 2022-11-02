<?php

namespace app\api\models;

use roaresearch\yii2\roa\{behaviors\Slug, hal\ARContract, hal\ContractTrait};
use yii\{base\Action, web\NotFoundHttpException};

/**
 * ROA contract to handle shop records.
 */
class Shop extends \app\models\Shop implements ARContract
{
    use ContractTrait {
        getLinks as getContractLinks;
    }

    /**
     * @inheritdoc
     */
    protected string $employeeClass = Employee::class;

    /**
     * @inheritdoc
     */
    protected function slugBehaviorConfig(): Slug
    {
        return new class (['owner' => $this]) extends Slug {
            public string $resourceName = 'shop';

            public function checkAccess(
                array $params = [],
                ?Action $action = null
            ): void {
                if (
                    isset($params['shop_id'])
                    && $this->owner->id != $params['shop_id']
                ) {
                    throw new NotFoundHttpException(
                        'Shop not associated to element.'
                    );
                }
            }
        };
    }

    /**
     * @inheritdoc
     */
    public function getLinks()
    {
        return array_merge($this->getContractLinks(), [
            'employee' => $this->getSelfLink() . '/employee',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return ['id', 'name'];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['employees'];
    }
}

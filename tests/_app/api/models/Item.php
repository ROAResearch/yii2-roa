<?php

namespace app\api\models;

use roaresearch\yii2\roa\{behaviors\Slug, hal\ARContract, hal\ContractTrait};
use yii\{base\Action, web\NotFoundHttpException};

/**
 * ROA contract to handle item records.
 */
class Item extends \app\models\Item implements ARContract
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
            public string $resourceName = 'item';

            public function checkAccess(
                array $params = [],
                ?Action $action = null
            ): void {
                if (
                    isset($params['item_id'])
                    && $this->owner->id != $params['item_id']
                ) {
                    throw new NotFoundHttpException(
                        'Item not associated to element.'
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
            'sale' => $this->getSelfLink() . '/sale',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['sale'];
    }
}

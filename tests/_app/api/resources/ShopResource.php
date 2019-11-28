<?php

namespace app\api\resources;

use roaresearch\yii2\roa\actions\SoftDelete as ActionSoftDelete;
use app\api\models\{Shop, ShopSearch};
use yii\db\ActiveQuery;

/**
 * CRUD resource for `Shop` records
 * @author Carlos (neverabe) Llamosas <carlos@invernaderolabs.com>
 */
class ShopResource extends \roaresearch\yii2\roa\controllers\Resource
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['delete']['class'] = ActionSoftDelete::class;

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public $modelClass = Shop::class;

    /**
     * @inheritdoc
     */
    public $searchClass = ShopSearch::class;

    /**
     * @inheritdoc
     */
    protected function baseQuery(): ActiveQuery
    {
        return parent::baseQuery()->andFilterDeleted();
    }
}

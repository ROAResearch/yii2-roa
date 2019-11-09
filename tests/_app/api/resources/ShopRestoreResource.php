<?php

namespace app\api\resources;

use app\api\models\Shop;
use roaresearch\yii2\roa\controllers\RestoreResource;
use yii\db\ActiveQuery;

/**
 * Resource to
 */
class ShopRestoreResource extends RestoreResource
{
    /**
     * @inheritdoc
     */
    public $modelClass = Shop::class;

    /**
     * @inheritdoc
     */
    protected function baseQuery(): ActiveQuery
    {
        return parent::baseQuery()->andFilterDeleted('s', true);
    }
}


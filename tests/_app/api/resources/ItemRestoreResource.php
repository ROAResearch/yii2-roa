<?php

namespace app\api\resources;

use app\api\models\Item;
use roaresearch\yii2\roa\controllers\RestoreResource;
use yii\db\ActiveQuery;

/**
 * Resource to
 */
class ItemRestoreResource extends RestoreResource
{
    /**
     * @inheritdoc
     */
    public $modelClass = Item::class;

    /**
     * @inheritdoc
     */
    protected function baseQuery(): ActiveQuery
    {
        return parent::baseQuery()->andFilterDeleted('i', true);
    }
}


<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;
use Yii;

class View extends Action
{
    /**
     * @return ARContract
     * @param mixed $id
     */
    public function run($id): ARContract
    {
        $this->checkAccess(
            ($model = $this->findModel($id)),
            Yii::$app->getRequest()->getQueryParams()
        );

        return $model;
    }
}

<?php

namespace roaresearch\yii2\roa\actions;

use roarsearch\yii2\roa\hal\ARContract;
use Yii;

class View extends Action
{
    /**
     * @return ActiveDataProvider
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

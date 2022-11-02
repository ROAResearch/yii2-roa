<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;

trait DeleteResponseTrait
{
    protected function successResponse(ARContract $model)
    {
        \Yii::$app->getResponse()->setStatusCode(204);
    }
}

<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;

class Action extends \yii\rest\Action
{
    /**
     * @param ARContract $model
     * @param array $params
     * @throws \yii\web\HTTPException
     */
    public function checkAccess(ARContract $model, array $params = []): void
    {
        $this->controller->checkAccess($this->id, $model, $params);
        $model->checkAccess($params, $this);
    }
}

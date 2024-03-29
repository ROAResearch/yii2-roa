<?php

namespace roaresearch\yii2\roa\controllers;

/**
 * Shows the fact sheet for the api version its contained.
 *
 * @property \roaresearch\yii2\roa\modules\ApiVersion $module
 *
 * @author Angel (Faryshta) Guevara <aguevara@tecnocen.com>
 */
class ApiVersionController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * List of all the resources available for the parent module api version.
     *
     * @return array
     */
    public function actionIndex(): array
    {
        return $this->module->getFactSheet();
    }
}

<?php

namespace roaresearch\yii2\roa\controllers;

use Yii;
use yii\{
    helpers\ArrayHelper,
    web\GoneHttpException,
    web\NotFoundHttpException
};

/**
 * Lists all the available versions for an api and handles error responses.
 *
 * @property \roaresearch\yii2\roa\modules\ApiContainer $module
 *
 * @author Angel (Faryshta) Guevara <aguevara@tecnocen.com>
 */
class ApiContainerController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Lists the available versions and their respective stability for the
     * parent module.
     *
     * @return string[]
     */
    public function actionIndex(): array
    {
        return ArrayHelper::map(
            $this->module->versionModules,
            'id',
            'factSheet'
        );
    }

    /**
     * Handles the exceptions catched by the system bootstrapping process.
     * @return \Exception
     */
    public function actionError(): \Exception
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(
                Yii::t('yii', 'Page not found.')
            );
        }

        Yii::$app->response->statusCode = isset($exception->statusCode)
            ? $exception->statusCode
            : 500;

        return $exception;
    }

    /**
     * Action shown when a resource is  no longer available.
     *
     * @throws GoneHttpException
     */
    public function actionGone()
    {
        throw new GoneHttpException(
            'The resource you seek is obsolete, visit '
                . $this->module->getSelfLink()
                . ' to get the fact sheets of all available versions.'
        );
    }
}

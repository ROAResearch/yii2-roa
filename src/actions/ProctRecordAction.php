<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;
use Yii;
use yii\web\{HttpException, ServerErrorHttpException};

/**
 * Proct a record. Returns the record on success, throws exception on error.
 *
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
abstract class ProctRecordAction extends Action
{
    /**
     * @var string error message thrown when proct fails.
     */
    protected string $errorMessage = 'Process failed for unknown reasons.';

    /**
     * Procts a record.
     *
     * @param mixed $id the identifier value.
     * @return ARContract the record after successful proct
     */
    public function run($id): ?ARContract
    {
        $this->checkAccess(
            ($model = $this->findModel($id)),
            ($params = Yii::$app->request->getQueryParams())
        );
        $this->proct($model, $params) ?: throw $this->errorException();

        return $this->successResponse($model);
    }

    /**
     * @param ARContract $model
     * @param array $params the request params
     * @return bool whether the proct was successful
     */
    abstract protected function proct(ARContract $model, array $params): bool;

    /**
     * @return HttpException
     */
    protected function errorException(): HttpException
    {
        return new ServerErrorHttpException($this->errorMessage);
    }

    /**
     * @param ARContract $model
     */
    protected function successResponse(ARContract $model)
    {
        return $model;
    }
}

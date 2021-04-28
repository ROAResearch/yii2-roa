<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\ŗoa\hal\ARContract;
use Yii;
use yii\{base\Model, web\ServerErrorHttpException};

/**
 * Action to create a record.
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
class Create extends Action
{
    use LoadFileTrait;

    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public string $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Creates a new model.
     * @return ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run(): ARContract
    {
        $request = Yii::$app->getRequest();
        $modelClass = $this->modelClass;
        /* @var $model ARContract */
        $model = new $modelClass([
            'scenario' => $this->scenario,
        ]);
        $model->load(
            $request->getQueryParams() + $request->getBodyParams(),
            ''
        );
        $this->checkAccess($model, $request->getQueryParams());
        $model->load($this->parseFileAttributes(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location', $model->getSelfLink());
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException(
                'Failed to create the object for unknown reason.'
            );
        }

        return $model;
    }
}

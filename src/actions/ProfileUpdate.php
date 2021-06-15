<?php

namespace roaresearch\yii2\roa\actions;

use Yii;
use yii\{base\Model, web\ServerErrorHttpException};

class ProfileUpdate extends \yii\rest\Action
{
    use LoadFileTrait;

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string[] that defines which columns will be recibe files
     */
    public $fileAttributes = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * Updates the information of the logged user.
     *
     * @return \yii\db\ActiveRecordInterface
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run()
    {
        /* @var $model \yii\db\ActiveRecordInterface */
        $model = Yii::$app->user->identity;
        $model->scenario = $this->scenario;
        $model->load(
            Yii::$app->getRequest()->getBodyParams() + $this->parseFileAttributes(),
            ''
        );
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException(
                'Failed to update the object for unknown reason.'
            );
        }

        return $model;
    }
}

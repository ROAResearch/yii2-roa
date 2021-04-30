<?php

namespace roaresearch\yii2\roa\actions;

use roaresearch\yii2\roa\hal\ARContract;
use Yii;
use yii\{base\Model, web\ServerErrorHttpException};

/**
 * Action to update the attributes in a record.
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigital.mx>
 */
class Update extends ProctRecordAction
{
    use LoadFileTrait;

    /**
     * @inheritdoc
     */
    protected string $errorMessage = 'Update failed for unknown reasons.';

    /**
     * @var string the scenario to be assigned to the model before it is
     * validated and updated.
     */
    public string $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @inheritdoc
     */
    protected function proct(ARContract $model, array $params): bool
    {
        $model->scenario = $this->scenario;
        $model->load(
            Yii::$app->request->getBodyParams() + $this->parseFileAttributes(),
            ''
        );

        return false !== $model->save() || $model->hasErrors();
    }
}

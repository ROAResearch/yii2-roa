<?php

namespace roaresearch\yii2\roa\controllers;

use roaresearch\yii2\roa\actions\{FileStream, ProfileUpdate, ProfileView};
use Yii;
use yii\{base\Model, filters\VerbFilter, rest\OptionsAction};

class ProfileResource extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // content negotiator, autenticator, etc moved by default to
            // api container
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    /**
     * @inheridoc
     */
    protected function verbs()
    {
        $verbs = ['GET', 'PUT', 'PATCH', 'OPTIONS'];

        return [
            'view' => $verbs,
            'update' => $verbs,
            'options' => $verbs,
        ];
    }

    /**
     * @inheridoc
     */
    public function actions()
    {
        return [
            'view' => ['class' => ProfileView::class],
            'update' => [
                'class' => ProfileUpdate::class,
                'scenario' => $this->updateScenario,
            ],
            'options' => ['class' => OptionsAction::class],
            'file-stream' => [
                'class' => FileStream::class,
                'modelClass' => Yii::$app->identityClass,
                'findModel' => function () {
                    return Yii::$app->identity;
                },
            ],
        ];
    }
}

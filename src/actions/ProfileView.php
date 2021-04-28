<?php

namespace roaresearch\yii2\roa\actions;

use Yii;
use yii\web\IdentityInterface;

class ProfileView extends \yii\rest\Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * Shows the information of the logged user.
     *
     * @return IdentityInterface
     */
    public function run(): IdentityInterface
    {
        return Yii::$app->user->identity;
    }
}

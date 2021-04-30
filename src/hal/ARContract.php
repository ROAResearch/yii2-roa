<?php

namespace roaresearch\yii2\roa\hal;

use yii\db\ActiveRecordInterface;

interface ARContract extends ActiveRecordInterface, Contract
{
    public function setScenario($value);

    public function isRelationPopulated($name);

    public function load($data, $formName = null);

    public function hasErrors($attribute = null);
}

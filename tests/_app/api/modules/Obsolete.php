<?php

namespace app\api\modules;

use roaresearch\yii2\roa\controllers\ProfileResource;
use roaresearch\yii2\roa\urlRules\SingleRecord;

class Obsolete extends \roaresearch\yii2\roa\modules\ApiVersion
{

    public $releaseDate = '2010-06-15';
    public $deprecationDate = '2016-01-01';
    public $obsoleteDate = '2017-12-31';

    /**
     * @inheritdoc
     */
    public $resources = [
        'profile' => [
            'class' => ProfileResource::class,
            'urlRule' => ['class' => SingleRecord::class],
        ],
    ];

    public $apidoc = 'http://mockapi.com/v1';
}

<?php

namespace app\api\modules;

use roaresearch\yii2\roa\{controllers\ProfileResource, urlRules\SingleRecord};

class Obsolete extends \roaresearch\yii2\roa\modules\ApiVersion
{

    public ?string $releaseDate = '2010-06-15';
    public ?string $deprecationDate = '2016-01-01';
    public ?string $obsoleteDate = '2017-12-31';

    /**
     * @inheritdoc
     */
    public array $resources = [
        'profile' => [
            'class' => ProfileResource::class,
            'urlRule' => ['class' => SingleRecord::class],
        ],
    ];

    public ?string $apidoc = 'http://mockapi.com/v1';
}

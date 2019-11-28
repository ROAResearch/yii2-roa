<?php

use app\api\modules\Obsolete;
use app\api\modules\Version;
use app\models\User;
use roaresearch\yii2\roa\modules\ApiVersion;
use roaresearch\yii2\roa\urlRules\SingleRecord;

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common.php',
    [
        'id' => 'yii2-roa-tests',
        'bootstrap' => ['api'],
        'modules' => [
            'api' => [
                'class' => roaresearch\yii2\roa\modules\ApiContainer::class,
                'identityClass' => User::class,
                'versions' => [
                    'v1' => [
                        'class' => Version::class,
                    ],
                    'dev' => [
                        'class' => ApiVersion::class,
                    ],
                    'stable' => [
                        'class' => ApiVersion::class,
                    ],
                    'deprecated' => [
                        'class' => ApiVersion::class,
                    ],
                    'obsolete' => [
                        'class' => Obsolete::class,
                    ],
                ],
            ],
        ],
        'components' => [
            'mailer' => [
                'useFileTransport' => true,
            ],
            'user' => ['identityClass' => app\models\User::class],
            'urlManager' => [
                'showScriptName' => true,
                'enablePrettyUrl' => true,
            ],
            'request' => [
                'cookieValidationKey' => 'test',
                'enableCsrfValidation' => false,
            ],
        ],
        'params' => [],
    ]
);

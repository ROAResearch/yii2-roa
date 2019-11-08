Action Filters
=================

The classes that extend [ActionFilter] define behaviors for
run before and after an action is executed.

Yii2 ActionFilter
--------------------

Most filters such as [Cors], [HostControl], [HttpCache],
[PageCache] and [RateLimiter] are not implemented by default and can be
use as detailed in the [Yii2 Guide].

Functionality Implemented in ROA
--------------------------------

There are functionalities of third parties or that already have a default support in
ROA that it is necessary to highlight.

### Authentication

Authentication [OAuth2] is supported with filters:

- `roaresearch\yii2\oauth2server\filters\auth\CompositeAuth`
- `yii\filters\auth\HttpBearerAuth`
- `yii\filters\auth\QueryParamAuth`

```php
public function behaviors()
{
    return [
        'authenticator' => [
            'class' => CompositeAuth::class,
            'oauth2Module' => 'api/oauth2',
            'authMethods' => [
                ['class' => HttpBearerAuth::class],
                [
                    'class' => QueryParamAuth::class,
                    // !Important, GET request parameter to get the token.
                    'tokenParam' => 'accessToken',
                ],
            ],
        ],
    ];
}
```

> By default this is defined in `roaresearch\yii2\roa\modules\ApiContainer::behaviors()`.
> When extending this method, this must be taken into account.

### Content Negotiation

Content Negotiation is supported with the filter
`yii\filters\ContentNegotiator`.

```php
public function behaviors()
{
    return [
        'contentNegotiator' => [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => 'json',
                'application/xml' => 'xml',
            ],
            'languages' => [
                'en',
                'de',
            ],
        ],
    ];
}
```

> By default this is defined in `roaresearch\yii2\roa\modules\ApiContainer::behaviors()`.
> When extending this method, this must be taken into account.

### Access control

Access control is more complex since it is not only supported with the filter
`yii\filters\AccessControl` if not also with methods
`roaresearch\yii2\roa\controllers\Resource::checkAccess()`,
`roaresearch\yii2\roa\behaviors\Slug::checkAccess()` and paradigms like [RBAC].

[Access Control Article in ROA]

Methods of Use
--------------

Action filters can be used in various ways in both resources
as in the container module or in the version modules depending on the
scope that is needed for each functionality.

### Append in Api Container

```php
use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\roa\modules\ApiContainer;
use yii\filters\auth\{HttpBearerAuth, QueryParamAuth};
use yii\helpers\ArrayHelper;

class Api extends ApiContainer
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => $this->getOauth2Module(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
                 // no requerir token para generar token
                'except' => [$this->oauth2ModuleId . '/*'],
            ],
        ]);
    }
}
```

This causes all the versions registered in the container.

> By default the method contains support for authentication and content negotiator
> so these functions must be redefined when writing the
> method or invoke `parent::behaviors()` as in the example

Append in Api Version
---------------------

It can be appended to an api version instance from the container:

```php
use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\roa\modules\ApiContainer;
use yii\filters\auth\{HttpBearerAuth, QueryParamAuth};

class Api extends ApiContainer
{
    public $versions = [
        'v1' => [
            'class' => v1/Version::class,
            'as authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => 'api/oauth2',
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
            ],
        ],
    ];
}
```

or in the class declaration.

```php
use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\roa\modules\ApiContainer;
use yii\filters\auth\{HttpBearerAuth, QueryParamAuth};

class V1 extends ApiVersion
{
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => $this->owner->getOauth2Module(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
            ],
        ];
    }
}
```

Append to Resource
----------------

Finally you can define the authentication by resource individually.

This can be done from the version to which it belongs.

```php
use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\roa\modules\ApiContainer;
use yii\filters\auth\{HttpBearerAuth, QueryParamAuth};

class V1 extends ApiVersion
{
    public $resources = [
        'shop' => [
            'class' => resources/ShopResource::class,
            'as authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => 'api/oauth2',
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
            ],
        ],
    ];
}
```

or in the class declaration.

```php
use roaresearch\yii2\oauth2server\filters\auth\CompositeAuth;
use roaresearch\yii2\roa\controllers\Resource;
use yii\filters\auth\{HttpBearerAuth, QueryParamAuth};

class ShopResource extends Resource
{
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'oauth2Module' => $this->owner->owner->getOauth2Module(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::class],
                    [
                        'class' => QueryParamAuth::class,
                        // !Important, GET request parameter to get the token.
                        'tokenParam' => 'accessToken',
                    ],
                ],
            ],
        ];
    }
}
```

> If an ActionFilter is used with more than one of the described methods,
> all will be executed at the same time so it is necessary to use `$except` and `$only`
> to avoid collisions.

[ActionFilter]: https://www.yiiframework.com/doc/api/2.0/yii-base-actionfilter
[Cors]: https://www.yiiframework.com/doc/api/2.0/yii-base-cors
[HostControl]: https://www.yiiframework.com/doc/api/2.0/yii-base-hostcontrol
[HttpCache]: https://www.yiiframework.com/doc/api/2.0/yii-base-httpcache
[PageCache]: https://www.yiiframework.com/doc/api/2.0/yii-base-pagecache
[RateLimmiter]: https://www.yiiframework.com/doc/api/2.0/yii-base-actionfilter
[Guia de Yii2]: https://www.yiiframework.com/doc/guide/2.0/en/structure-filters
[OAuth2]: https://oauth.net/2/
[RBAC]: https://www.yiiframework.com/doc/guide/2.0/en/security-authorization#rbac
[Articulo de Control de Acceso en ROA]: access-control.md

Testing classes
=================

Help classes and interfaces are provided to facilitate writing tests of
[Codeception Yii] focused on [Rest Tests].

`roaresearch\yii2\roa\test\Tester`
----------------------------------

Interface that requests specific methods for ROA to `Codeception\Actor`. It's used
when asking the class `ApiTester` to implement it.

It also provides a trait `roaresearch\yii2\roa\test\TesterTrait` which implements
all the necessary methods.

```php

use Codeception\Actor;
use roaresearch\yii2\roa\test\{
    Tester as RoaTester,
    TesterTrait as RoaTesterTrait
};

class ApiTester extends Actor implements RoaTester
{
    use RoaTesterTrait;
    // other traits and methods.
}
```

`roaresearch\yii2\roa\test\AbstractAccessTokenCest`
---------------------------------------------------

Abstract class that provides protected methods that facilitate creation tests
of tokens.

```php
class V1TokenCest extends \roaresearch\yii2\roa\test\AbstractAccessTokenCest
{
    /**
     * @dataprovider tokenData
     */
    public function oauth2Token(ApiTester $I, Example $I)
    {
        $I->wantTo('Generate OAuth2 Token');
        $I->generateToken($I, $example);
    }

    protected function tokenData()
    {
        return [
            [
                'client' => 'http_client',
                'clientPass' => 's3cr3t',
                'user' => 'aguevara',
                'userPass' => '244466666',
                'tokenName' => 'aguevara',
            ],
        ];
    }
}
```

`roaresearch\yii2\roa\test\AbstractResourceCest`
------------------------------------------------

Abstract class that provides protected methods that facilitate testing of resources
ROA and their respective verbs.

```php
class UserCest extends \roaresearch\yii2\roa\test\AbstractResourceCest
{
    /**
     * @dataprovider indexData
     */
    public function index(ApiTester $I, Example $I)
    {
        $I->wantTo('Generate Users List');
        $I->generateToken($I, $example);
    }

    protected function recordJsonType()
    {
        return [
            'id' => 'integer',
            'username' => 'string',
            'email' => 'string',
            '_links' => [
                'self' => ['href' => 'string:url'],
            ]
        ];
    }

    protected function getRoutePattern()
    {
        return 'users';
    }

    protected function indexData()
    {
        return [
            [
                'client' => 'http_client',
                'clientPass' => 's3cr3t',
                'user' => 'aguevara',
                'userPass' => '244466666',
                'tokenName' => 'aguevara',
            ],
        ];
    }
}
```

[Codeception Api]: http://codeception.com/for/yii
[Pruebas Rest]: http://codeception.com/docs/10-WebServices#REST

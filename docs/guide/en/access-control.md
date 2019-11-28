Access control
==============

Access control in Yii2 ROA is complicated to cover several cases
of use.

yii\\filters\\AccessControl
-------------------------

[AccessControl](https://www.yiiframework.com/doc/api/2.0/yii-filters-accesscontrol)
is an ActionFilter of Yii2 that allows declaring access rules which can be
axexar in controllers or modules.

Al ser un ActionFilter de Yii2 se puede usar como se describe en la guia de
[Action Filters](action-filters.md)

It covers the use case of giving support to complete modules or groups of
controllers by configuring `$only` and `$except`.

CheckAccess
-----------

There are several methods declared as 'checkAccess' covering different cases of
use and ways to be declared.

### roaresearch\\yii2\\roa\\controllers\\Resource::checkAccess()

Extends from
[yii\\rest\\ActiveController]
It covers the use case of validating access for a specific controller.

```php
class ShopResource extends \roaresearch\yii2\roa\Resource
{
    public function checkAccess($action, $model = null, $params = [])
    {
        // ...
    }
}
```

### roaresearch\\yii2\\roa\\actions\\Action::$checkAcccess

Extends from [yii\\rest\\Action] covers the use case of validating an action
specifies

The signature of the anonymous function changes by adding a `$ params` argument
contains the parameters sent through POST and GET.


```php
use roaresearch\yii2\roa\{Resource, Action};
use yii\db\ActiveRecordInterface;

class ShopResource extends Resource
{
    public function actions()
    {
        $actions = parent::actions();

        $actions['create']['checkAccess'] = function (
            Action $action,
            ActiveRecordInterface $model = null,
            array $params = []
        ) {
            // ...
        }

        return $actions;
    }
}
```

### roaresearch\\yii2\\roa\\behaviors\\Slug::$checkAccess

The Slug class is appended to the models to generate nested records links
and also check that you have access to each section of the roa route.

For example if you have a request to the path `shop / 1 / section / 3 / aisle / 5`
the validation of `checkAccess` is executed in` aisle` of id 5, section of id 3
and `shop` of id 1 in that order.

```php
use roaresearch\yii2\roa\behaviors\Slug;

class Shop extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::class,
                'checkAccess' => function (array $params) {
                    // ...
                },
            ],
        ];
    }
}
```

Where `$ params` are the parameters sent through POST and GET.

[yii\\rest\\ActiveController]: https://www.yiiframework.com/doc/api/2.0/yii-rest-activecontroller
[yii\\rest\\Action]: https://www.yiiframework.com/doc/api/2.0/yii-rest-action

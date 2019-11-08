Slug Behavior
=============

Some resources need to be configured in an "aninated" way so that the
The representational route includes the information of the parents for its consumption.

`store/1/warehouse/3/section/5`

With the requirement that if you do not have the permissions to access `shop/1`
neither will be able to access any of the nested services.

The class `roaresearch\yii2\roa\behaviors\Slug` provides functionality for records
whose resources are nested with other resources of the same version.

Use
---

```php
use roaresearch\yii2\roa\behaviors\Slug;

public function behaviors()
{
    return [
        [
            'class' => Slug::class,
            'resourceName' => 'section', // will be used to create the links
            'parentSlugRelation' => 'warehouse', // nesting relationship
            'checkAccess' => function ($params) {
                $user = Yii::$app->getUser();
                if (!$user->can('manager')
                    && !$this->responsable_id == $user->id
                ) {
                    throw new \yii\web\ForbiddenHttpException(
                        'You do not have permission to edit this record.'
                    );
                }

                if (isset($params['warehouse_id'])
                    && $this->id != $params['warehouse_id']
                ) {
                    throw new \yii\web\NotFoundHttpException(
                        "Record not associated to the store {$params['warehouse_id']}."
                    );
                }
            }
        ]
    ];
}


public function getWarehouse()
{
    return $this->hasOne(Warehouse::class, ['id' => 'warehouse_id']);
}
```

Method checkAccess()
--------------------

The method `roaresearch\yii2\roa\behaviors\Slug::checkAccess()` serves to make
each Check if it is available for access. This method is invoked retroactively
on the resources registered by the property `parentSlugRelation`.

`store/1/warehouse/3/section/5`

When invoking `checkAccess()` is invoked for Store record with id 1,
Warehouse record with id 3 and Section record with id 5.

The method receives as parameter an `array` with the parameters `GET`
received in the petition. And it must throw exceptions `yii\web\HttpException`
when access is not allowed.

The complete signature of the firm is

```php
function checkAccess(string[] $params)
    throws \yii\web\HttpException
{
}
```

Links
-----

Slug behavior has `getSelfLink()` and `getSlugLinks()` methods which
automate the creation of relational links by calling recursively
the related links of the relationships defined in `parentSlugRelation`

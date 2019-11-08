Routing
=============

yii2-roa defines 3 classes that extend from `yii\rest\UrlRule` to access the
resources individually.

In the `roaresearch\yii2\roa\modules\ApiVersion::$resources` property you can
add a special `urlRule` key which individually configures the rule routing for
each resource.

```php
use roaresearch\yii2\roa\urlRules\File as FileUrlRule;

class V1 extends \roaresearch\yii2\roa\modules\ApiVersion {
    public $resources = [
        'documento' => [
            'class' => DocumentoResource::class,
            'urlRule' => [
                'class' => FileUrlRule::class, // rule class
                'ext' => ['csv', 'xls', 'pdf', 'doc'], // supported extensions
            ]
        ]
    ];
}
```

This allows sending a request `api/v1/document/1.doc` to execute action
`file-stream` of the` DocumentResource` resource.

The classes provided in this repository are

### roaresearch\\yii2\\roa\\urlRules\\Resource

Class by default, it allows to route the rest verbs and parameters for
resources with data collections, that is to say that they have lists of resources
make a request [GET].

### roaresearch\\yii2\\roa\\urlRules\\File

Similar to the previous class only adds support for routes with termination
`{id}.{ext}` where `{id}` is the identifier of a record and `{ext}` is a
valid extension configured in the `$ext` property of the class.

### roaresearch\\yii2\\roa\\urlRules\\SingleRecord

Routing that only supports one record and not collection, that is, action
`index` is not supported for example to access` profile` which is only a
registration for each user that accesses the system.

Optimizations
-------------

It is recommended to organize the rules of the resources most used at the
beginning to avoid repeating unnecessary processes.

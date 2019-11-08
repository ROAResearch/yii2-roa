Clase de Recurso ROA
====================

La clase `roaresearch\yii2\roa\controllers\Resource` implementa el
comportamiento general de un recurso en ROA.

Para poder utilizar la clase se requiere primero dar de alta el recurso en
`roaresearch\yii2\roa\modules\ApiVersion::$resources`, esta propiedad se
detalla en la guía de [Api Version](api-version.md).

Supongamos que se declara el siguiente recurso.

```php
    public $resources = [
        'store',
    ];
```

Luego se crea la clase del recurso

```php
use backend\api\v1\models\{Store, StoreSearch};

class StoreResource extends \roaresearch\yii2\roa\controllers\Resource
{
    public $modelClass = Store::class;
    public $searchClass = StoreSearch::class;
}
```

El modelo `Store` debe de implementar `roaresearch\yii2\roa\hal\Embeddable`.

El modelo `StoreSearch` debe implementar `roaresearch\yii2\roa\ResourceSearch` y en el
metodo `search()` devolver una instancia de `yii\data\DataProviderInterface` la
cual genere instancias de `Store`.

Si la propiedad `$searchClass` no se define entonces la busqueda se define por
defecto en base a las propiedades `$filterParams` y `$filterUser`.

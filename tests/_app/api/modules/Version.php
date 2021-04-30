<?php

namespace app\api\modules;

use app\api\resources\{
    EmployeeResource,
    EmployeeRestoreResource,
    ItemResource,
    ItemRestoreResource,
    SaleResource,
    SaleItemResource,
    ShopRestoreResource,
    ShopResource
};
use roaresearch\yii2\roa\{
    controllers\ProfileResource,
    modules\ApiVersion,
    urlRules\Profile as ProfileUrlRule,
    urlRules\File as FileUrlRule
};

class Version extends ApiVersion
{
    public ?string $releaseDate = '2020-06-15';
    public ?string $deprecationDate = '2025-01-01';
    public ?string $obsoleteDate = '2025-12-31';

    public const ITEM_ROUTE = 'item';
    public const ITEM_RESTORE_ROUTE = 'item-restore';

    public const SHOP_ROUTE = 'shop';
    public const SHOP_RESTORE_ROUTE = 'shop-restore';

    public const EMPLOYEE_ROUTE = self::SHOP_ROUTE . '/<shop_id:\d+>/employee';
    public const EMPLOYEE_RESTORE_ROUTE = self::SHOP_ROUTE
        . '/<shop_id:\d+>/employee-restore';

    public const SALE_ROUTE = self::EMPLOYEE_ROUTE . '/<employee_id:\d+>/sale';
    public const SALE_RESTORE_ROUTE = self::EMPLOYEE_ROUTE
        . '/<employee_id:\d+>/sale-recovery';

    public const SALE_ITEM_ROUTE = self::SALE_ROUTE . '/<sale_id:\d+>/item';

    /**
     * @inheritdoc
     */
    public array $resources = [
        'profile' => [
            'class' => ProfileResource::class,
            'urlRule' => ['class' => ProfileUrlRule::class],
        ],
        'file' => [
            'class' => FileResource::class,
            'urlRule' => [
                'class' => FileUrlRule::class,
            ],
        ],

        self::ITEM_ROUTE => ['class' => ItemResource::class],
        self::ITEM_RESTORE_ROUTE => ['class' => ItemRestoreResource::class],

        self::SHOP_ROUTE => ['class' => ShopResource::class],
        self::SHOP_RESTORE_ROUTE => ['class' => ShopRestoreResource::class],

        self::EMPLOYEE_ROUTE => ['class' => EmployeeResource::class],
        self::EMPLOYEE_RESTORE_ROUTE => [
            'class' => EmployeeRestoreResource::class,
        ],

        self::SALE_ROUTE => ['class' => SaleResource::class],
        self::SALE_RESTORE_ROUTE => ['class' => SaleRestoreResource::class],

        self::SALE_ITEM_ROUTE => ['class' => SaleItemResource::class],
    ];

    public ?string $apidoc = 'http://mockapi.com/v1';
}

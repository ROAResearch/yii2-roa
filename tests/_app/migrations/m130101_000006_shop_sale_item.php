<?php

class m130101_000006_shop_sale_item extends \roaresearch\yii2\migrate\CreateTableMigration
{
    /**
     * @inhertidoc
     */
    public $defaultOnDelete = 'RESTRICT';
    
    /**
     * @inhertidoc
     */
    public function getTableName(): string
    {
        return 'shop_sale_item';
    }

    /**
     * @inhertidoc
     */
    public function columns(): array
    {
        return [
            'item_id' => $this->normalKey(),
            'sale_id' => $this->normalKey(),
        ];
    }

    /**
     * @inhertidoc
     */
    public function compositePrimaryKeys(): array
    {
        return ['item_id', 'sale_id'];
    }

    /**
     * @inhertidoc
     */
    public function foreignKeys(): array
    {
        return [
            'item_id' => 'item',
            'sale_id' => 'shop_sale',
        ];
    }
}

<?php

class m130101_000004_shop_sale extends \roaresearch\yii2\migrate\CreateTableMigration
{

    /**
     * @inhertidoc
     */
    public function getTableName(): string
    {
        return 'shop_sale';
    }

    /**
     * @inhertidoc
     */
    public function columns(): array
    {
        return [
            'id' => $this->primaryKey(),
            'employee_id' => $this->normalKey(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
        ];
    }

    /**
     * @inhertidoc
     */
    public function foreignKeys(): array
    {
        return [
            'employee_id' => 'shop_employee',
        ];
    }
}

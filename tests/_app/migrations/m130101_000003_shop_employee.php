<?php

class m130101_000003_shop_employee extends \roaresearch\yii2\migrate\CreateTableMigration
{

    /**
     * @inhertidoc
     */
    public function getTableName(): string
    {
        return 'shop_employee';
    }

    /**
     * @inhertidoc
     */
    public function columns(): array
    {
        return [
            'id' => $this->primaryKey(),
            'shop_id' => $this->normalKey(),
            'name' => $this->string(32)->notNull()->unique(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
        ];
    }

    /**
     * @inhertidoc
     */
    public function foreignKeys(): array
    {
        return ['shop_id' => 'shop'];
    }
}

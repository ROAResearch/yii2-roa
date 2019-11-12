<?php

class m130101_000001_user extends \roaresearch\yii2\migrate\CreateTableMigration
{

    /**
     * @inhertidoc
     */
    public function getTableName(): string
    {
        return 'user';
    }

    /**
     * @inhertidoc
     */
    public function columns(): array
    {
        return [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
        ];
    }
}

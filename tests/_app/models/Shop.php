<?php

namespace app\models;

/**
 * Model class for table `{{%shop}}`
 *
 * @property integer $id
 * @property string $name
 *
 * @property Employee[] $employee
 */
class Shop extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    /**
     * @var string full class name of the model used in the relation
     * `getEmployee()`.
     */
    protected string $employeeClass = Employee::class;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'min' => 6],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Shop Name',
        ];
    }

    /**
     * @return SoftDeleteQuery
     */
    public function getEmployees(): SoftDeleteQuery
    {
        return $this->hasMany($this->employeeClass, ['shop_id' => 'id'])
            ->inverseOf('shop');
    }
}

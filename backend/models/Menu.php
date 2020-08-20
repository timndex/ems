<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_menu".
 *
 * @property string $menu_id
 * @property string $menu_name
 * @property string $menu_active_department
 * @property string $menu_icon
 * @property string|null $menu_url
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'menu_name', 'menu_active_department', 'menu_icon', 'created_at', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['menu_id', 'menu_name', 'menu_icon', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['menu_url'], 'string', 'max' => 200],
            [['menu_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'menu_name' => 'Menu Name',
            'menu_active_department' => 'Menu Active Department',
            'menu_icon' => 'Menu Icon',
            'menu_url' => 'Menu Url',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
      public function getdepartmentName(){
        //return $this->hasOne(Departments::className(), ['departments_id'=>'menu_id']);
        return $this->hasOne(Departments::className(), ['departments_id'=>'menu_active_department']);
    }
    public function getsubCategorymenu(){
        return $this->hasMany(MenuSubcategory::className(), ['menu_id'=>'menu_id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_menu_subcategory".
 *
 * @property int $id
 * @property string|null $menu_id
 * @property string|null $subcategory
 * @property string|null $subcategory_url
 * @property string|null $status
 */
class MenuSubcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_menu_subcategory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'subcategory', 'subcategory_url', 'status'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'subcategory' => 'Subcategory',
            'subcategory_url' => 'Subcategory Url',
            'status' => 'Status',
        ];
    }
}

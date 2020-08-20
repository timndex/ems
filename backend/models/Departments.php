<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_departments".
 *
 * @property string $departments_id
 * @property string $departments_name
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departments_id', 'departments_name', 'created_at', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['departments_id', 'departments_name', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['departments_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'departments_id' => 'Departments ID',
            'departments_name' => 'Departments Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
}

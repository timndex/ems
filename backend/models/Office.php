<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_office".
 *
 * @property string $office_id
 * @property string $office_name
 * @property string $office_location
 * @property string $created_by
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class Office extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_id', 'office_name', 'office_location', 'created_by', 'created_at', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['office_id', 'office_name', 'office_location', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['office_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'office_id' => 'Office ID',
            'office_name' => 'Office Name',
            'office_location' => 'Office Location',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
}

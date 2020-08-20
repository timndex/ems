<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_tax".
 *
 * @property int $id
 * @property string $tax_name
 * @property string $tax_amount
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int $status
 */
class Tax extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_tax';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_name', 'tax_amount', 'created_at', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['updated_by', 'status'], 'integer'],
            [['tax_name', 'tax_amount', 'created_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_name' => 'Tax Name',
            'tax_amount' => 'Tax Amount',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
}

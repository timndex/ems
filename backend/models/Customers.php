<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_customers".
 *
 * @property string $customer_id
 * @property string $customer_names
 * @property string $customer_phone
 * @property string|null $customer_contact_number
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_names', 'customer_phone', 'created_at', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['customer_id', 'customer_names', 'customer_phone', 'customer_contact_number', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['customer_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'customer_names' => 'Customer Names',
            'customer_phone' => 'Customer Phone',
            'customer_contact_number' => 'Customer Contact Number',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
     public function getsubCategory(){
        return $this->hasMany(MailingList::className(), ['customer_id'=>'customer_id']);
    }
}

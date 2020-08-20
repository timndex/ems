<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_sales".
 *
 * @property int $id
 * @property string $job_card_number
 * @property string $transaction_number
 * @property string $cash_sale_number
 * @property string $customer_id
 * @property string $payment
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_sales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_number', 'transaction_number', 'cash_sale_number', 'customer_id', 'payment', 'created_at', 'created_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['job_card_number', 'transaction_number', 'cash_sale_number', 'customer_id', 'payment', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_card_number' => 'Job Card Number',
            'transaction_number' => 'Transaction Number',
            'cash_sale_number' => 'Cash Sale Number',
            'customer_id' => 'Customer ID',
            'payment' => 'Payment',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
           public function getcustomerName(){
        return $this->hasOne(Customers::className(), ['customer_id'=>'customer_id']);
    }
}

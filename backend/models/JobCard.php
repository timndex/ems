<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_job_card".
 *
 * @property int $id
 * @property string $job_card_number
 * @property string $customer_id
 * @property string $cost
 * @property string $payment
 * @property string $tax_amount
 * @property string $sales_tax
 * @property string $total_amount
 * @property string $balance
 * @property string $transaction_type
 * @property string $created_at
 * @property string $office
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class JobCard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_job_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_number', 'customer_id', 'cost', 'payment', 'tax_amount', 'sales_tax', 'total_amount', 'balance', 'transaction_type', 'created_at', 'office', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['job_card_number', 'customer_id', 'cost', 'payment', 'tax_amount', 'sales_tax', 'total_amount', 'balance', 'transaction_type', 'office', 'created_by', 'updated_by'], 'string', 'max' => 100],
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
            'customer_id' => 'Customer ID',
            'cost' => 'Cost',
            'payment' => 'Payment',
            'tax_amount' => 'Tax Amount',
            'sales_tax' => 'Sales Tax',
            'total_amount' => 'Total Amount',
            'balance' => 'Balance',
            'transaction_type' => 'Transaction Type',
            'created_at' => 'Created At',
            'office' => 'Office',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
          public function getcustomerName(){
        return $this->hasOne(Customers::className(), ['customer_id'=>'customer_id']);
    }
     public function getsubCategoryjob(){
        return $this->hasMany(JobCardSub::className(), ['job_card_id'=>'id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_invoice_payments".
 *
 * @property int $id
 * @property string $job_card_number
 * @property string $invoice_id
 * @property string $transaction_number
 * @property string $customer_id
 * @property string $payment
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class InvoicePayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_invoice_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_number', 'invoice_id', 'transaction_number', 'customer_id', 'payment', 'created_at', 'created_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['job_card_number', 'invoice_id', 'transaction_number', 'customer_id', 'payment', 'created_by', 'updated_by'], 'string', 'max' => 100],
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
            'invoice_id' => 'Invoice ID',
            'transaction_number' => 'Transaction Number',
            'customer_id' => 'Customer ID',
            'payment' => 'Payment',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}

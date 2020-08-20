<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_invoice_sub".
 *
 * @property int $id
 * @property string $invoice_number
 * @property string $quantity
 * @property string $service_type
 * @property string $description
 * @property string $cost_qty
 */
class InvoiceSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_invoice_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_number', 'quantity', 'service_type', 'description', 'cost_qty'], 'required'],
            [['invoice_number', 'quantity', 'service_type', 'description', 'cost_qty'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_number' => 'Invoice Number',
            'quantity' => 'Quantity',
            'service_type' => 'Service Type',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
        ];
    }
}

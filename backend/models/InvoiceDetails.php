<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_invoice_details".
 *
 * @property int $id
 * @property string|null $invoice_number
 * @property string $items
 * @property string|null $quantity
 * @property string|null $description
 * @property string|null $cost_qty
 * @property string $totals
 */
class InvoiceDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_invoice_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['items', 'totals'], 'required'],
            [['invoice_number', 'items', 'quantity', 'description', 'cost_qty', 'totals'], 'string', 'max' => 100],
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
            'items' => 'Items',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
            'totals' => 'Totals',
        ];
    }
}

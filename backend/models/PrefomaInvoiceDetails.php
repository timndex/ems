<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_prefoma_invoice_details".
 *
 * @property int $id
 * @property string|null $prefoma_number
 * @property string|null $items
 * @property string|null $quantity
 * @property string|null $description
 * @property string|null $cost_qty
 * @property string|null $totals
 */
class PrefomaInvoiceDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_prefoma_invoice_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefoma_number', 'items', 'quantity', 'description', 'cost_qty', 'totals'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prefoma_number' => 'Prefoma Number',
            'items' => 'Items',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
            'totals' => 'Totals',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_invoice_total".
 *
 * @property int $id
 * @property string $invoice_number
 * @property string $total
 */
class InvoiceTotal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_invoice_total';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_number', 'total'], 'required'],
            [['invoice_number', 'total'], 'string', 'max' => 100],
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
            'total' => 'Total',
        ];
    }
}

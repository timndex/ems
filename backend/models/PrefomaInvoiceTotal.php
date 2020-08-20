<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_prefoma_invoice_total".
 *
 * @property int $id
 * @property string|null $prefoma_number
 * @property string|null $total
 */
class PrefomaInvoiceTotal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_prefoma_invoice_total';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefoma_number', 'total'], 'string', 'max' => 100],
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
            'total' => 'Total',
        ];
    }
}

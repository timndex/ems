<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_prefoma_invoice_sub".
 *
 * @property int $id
 * @property string|null $prefoma_number
 * @property int|null $job_card_id
 * @property string|null $quantity
 * @property string|null $service_type
 * @property string|null $description
 * @property string|null $cost_qty
 */
class PrefomaInvoiceSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_prefoma_invoice_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_id'], 'integer'],
            [['prefoma_number', 'quantity', 'service_type', 'description', 'cost_qty'], 'string', 'max' => 100],
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
            'job_card_id' => 'Job Card ID',
            'quantity' => 'Quantity',
            'service_type' => 'Service Type',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
        ];
    }
}

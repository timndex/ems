<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_cash_sale_sub".
 *
 * @property int $id
 * @property string|null $cash_sale_id
 * @property string|null $job_card_id
 * @property string|null $quantity
 * @property string|null $service_type
 * @property string|null $description
 * @property string|null $cost_qty
 */
class CashSaleSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_cash_sale_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cash_sale_id', 'job_card_id', 'quantity', 'service_type', 'description', 'cost_qty'], 'string', 'max' => 100],
            [['job_card_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cash_sale_id' => 'Cash Sale ID',
            'job_card_id' => 'Job Card ID',
            'quantity' => 'Quantity',
            'service_type' => 'Service Type',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
        ];
    }
}

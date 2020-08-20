<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_job_card_sub".
 *
 * @property int $id
 * @property string $job_card_id
 * @property string $quantity
 * @property string $service_type
 * @property string $description
 * @property string $cost_qty
 * @property string $discount
 * @property string $totals
 */
class JobCardSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_job_card_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_id', 'quantity', 'service_type', 'description', 'cost_qty', 'totals'], 'required'],
            [['job_card_id', 'quantity', 'service_type', 'description', 'cost_qty', 'discount', 'totals'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_card_id' => 'Job Card ID',
            'quantity' => 'Quantity',
            'service_type' => 'Service Type',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
            'discount' => 'Discount',
            'totals' => 'Totals',
        ];
    }
    public function getsubCategoryCashsalesub(){
        return $this->hasMany(CashSaleSub::className(), ['job_card_id'=>'id']);
    }
}

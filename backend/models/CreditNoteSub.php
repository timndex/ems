<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_credit_note_sub".
 *
 * @property int $id
 * @property string $credit_note_id
 * @property string $quantity
 * @property string $description
 * @property string $cost_qty
 * @property string $total_credit
 */
class CreditNoteSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_credit_note_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_note_id', 'quantity', 'description', 'cost_qty', 'total_credit'], 'required'],
            [['credit_note_id', 'quantity', 'description', 'cost_qty', 'total_credit'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_note_id' => 'Credit Note ID',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'cost_qty' => 'Cost Qty',
            'total_credit' => 'Total Credit',
        ];
    }
    
}

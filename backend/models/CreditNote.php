<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_credit_note".
 *
 * @property int $credit_note_id
 * @property string $job_card_number
 * @property string $customer_id
 * @property string $credit_note_num
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int $status
 */
class CreditNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_credit_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_card_number', 'customer_id', 'credit_note_num', 'amount', 'created_at', 'created_by', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['job_card_number', 'customer_id', 'credit_note_num', 'amount', 'created_by', 'updated_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'credit_note_id' => 'Credit Note ID',
            'job_card_number' => 'Job Card Number',
            'customer_id' => 'Customer ID',
            'credit_note_num' => 'Credit Note Num',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }
         public function getcustomerName(){
        return $this->hasOne(Customers::className(), ['customer_id'=>'customer_id']);
    }
      public function getsubCategorycredit(){
        return $this->hasMany(CreditNoteSub::className(), ['credit_note_id'=>'credit_note_id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_mailing_list".
 *
 * @property int $id
 * @property string|null $customer_id
 * @property string|null $email
 */
class MailingList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_mailing_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'email'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'email' => 'Email',
        ];
    }
}

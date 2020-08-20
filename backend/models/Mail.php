<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_mail".
 *
 * @property int $id
 * @property string $subject
 * @property string $message
 */
class Mail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_mail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'message'], 'required'],
            [['subject', 'message'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'message' => 'Message',
        ];
    }
}

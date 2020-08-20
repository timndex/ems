<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "table_mpesa_transaction".
 *
 * @property int $mpesa_trans_id
 * @property string $TransactionType
 * @property string $TransID
 * @property string $TransTime
 * @property string $TransAmount
 * @property string $BusinessShortCode
 * @property string $BillRefNumber
 * @property string $InvoiceNumber
 * @property string $OrgAccountBalance
 * @property string $ThirdPartyTransID
 * @property string $MSISDN
 * @property string $FirstName
 * @property string|null $MiddleName
 * @property string|null $LastName
 */
class MpesaTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'table_mpesa_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TransactionType', 'TransID', 'TransTime', 'TransAmount', 'BusinessShortCode', 'BillRefNumber', 'InvoiceNumber', 'OrgAccountBalance', 'ThirdPartyTransID', 'MSISDN', 'FirstName'], 'required'],
            [['TransactionType', 'TransID', 'TransTime', 'TransAmount', 'BusinessShortCode', 'BillRefNumber', 'InvoiceNumber', 'OrgAccountBalance', 'ThirdPartyTransID', 'MSISDN', 'FirstName', 'MiddleName', 'LastName'], 'string', 'max' => 200],
            [['TransID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mpesa_trans_id' => 'Mpesa Trans ID',
            'TransactionType' => 'Transaction Type',
            'TransID' => 'Trans ID',
            'TransTime' => 'Trans Time',
            'TransAmount' => 'Trans Amount',
            'BusinessShortCode' => 'Business Short Code',
            'BillRefNumber' => 'Bill Ref Number',
            'InvoiceNumber' => 'Invoice Number',
            'OrgAccountBalance' => 'Org Account Balance',
            'ThirdPartyTransID' => 'Third Party Trans ID',
            'MSISDN' => 'Msisdn',
            'FirstName' => 'First Name',
            'MiddleName' => 'Middle Name',
            'LastName' => 'Last Name',
        ];
    }
}

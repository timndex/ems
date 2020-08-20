<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MpesaTransaction;

/**
 * MpesaTransactionSearch represents the model behind the search form of `app\models\MpesaTransaction`.
 */
class MpesaTransactionSearch extends MpesaTransaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mpesa_trans_id'], 'integer'],
            [['TransactionType', 'TransID', 'TransTime', 'TransAmount', 'BusinessShortCode', 'BillRefNumber', 'InvoiceNumber', 'OrgAccountBalance', 'ThirdPartyTransID', 'MSISDN', 'FirstName', 'MiddleName', 'LastName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MpesaTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mpesa_trans_id' => $this->mpesa_trans_id,
        ]);

        $query->andFilterWhere(['like', 'TransactionType', $this->TransactionType])
            ->andFilterWhere(['like', 'TransID', $this->TransID])
            ->andFilterWhere(['like', 'TransTime', $this->TransTime])
            ->andFilterWhere(['like', 'TransAmount', $this->TransAmount])
            ->andFilterWhere(['like', 'BusinessShortCode', $this->BusinessShortCode])
            ->andFilterWhere(['like', 'BillRefNumber', $this->BillRefNumber])
            ->andFilterWhere(['like', 'InvoiceNumber', $this->InvoiceNumber])
            ->andFilterWhere(['like', 'OrgAccountBalance', $this->OrgAccountBalance])
            ->andFilterWhere(['like', 'ThirdPartyTransID', $this->ThirdPartyTransID])
            ->andFilterWhere(['like', 'MSISDN', $this->MSISDN])
            ->andFilterWhere(['like', 'FirstName', $this->FirstName])
            ->andFilterWhere(['like', 'MiddleName', $this->MiddleName])
            ->andFilterWhere(['like', 'LastName', $this->LastName]);

        return $dataProvider;
    }
}

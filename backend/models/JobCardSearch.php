<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JobCard;

/**
 * JobCardSearch represents the model behind the search form of `app\models\JobCard`.
 */
class JobCardSearch extends JobCard
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['job_card_number', 'customer_id', 'cost', 'payment', 'tax_amount', 'sales_tax', 'total_amount', 'balance', 'transaction_type', 'created_at', 'office', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = JobCard::find()->where(['office'=> \Yii::$app->user->identity->office_location]);

           
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
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'job_card_number', $this->job_card_number])
            ->andFilterWhere(['like', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'cost', $this->cost])
            ->andFilterWhere(['like', 'payment', $this->payment])
            ->andFilterWhere(['like', 'tax_amount', $this->tax_amount])
            ->andFilterWhere(['like', 'sales_tax', $this->sales_tax])
            ->andFilterWhere(['like', 'total_amount', $this->total_amount])
            ->andFilterWhere(['like', 'balance', $this->balance])
            ->andFilterWhere(['like', 'transaction_type', $this->transaction_type])
            ->andFilterWhere(['like', 'office', $this->office])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}

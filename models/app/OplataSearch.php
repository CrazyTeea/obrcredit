<?php

namespace app\models\app;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\Oplata;

/**
 * OplataSearch represents the model behind the search form of `app\models\app\Oplata`.
 */
class OplataSearch extends Oplata
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numberpp_id', 'bank_id', 'order_date', 'system_status', 'status'], 'integer'],
            [['payment_date', 'latter_number', 'latter_date', 'order_number', 'updated_at', 'created_at'], 'safe'],
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
        $query = Oplata::find();

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
            'numberpp_id' => $this->numberpp_id,
            'bank_id' => $this->bank_id,
            'payment_date' => $this->payment_date,
            'latter_date' => $this->latter_date,
            'order_date' => $this->order_date,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'system_status' => $this->system_status,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'latter_number', $this->latter_number])
            ->andFilterWhere(['like', 'order_number', $this->order_number]);

        return $dataProvider;
    }
}

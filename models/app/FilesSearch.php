<?php

namespace app\models\app;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\Files;

/**
 * FilesSearch represents the model behind the search form of `app\models\app\Files`.
 */
class FilesSearch extends Files
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'size', 'system_status'], 'integer'],
            [['name', 'extension', 'updated_at', 'created_at'], 'safe'],
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
        $query = Files::find();

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
            'size' => $this->size,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'system_status' => $this->system_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'extension', $this->extension]);

        return $dataProvider;
    }
}

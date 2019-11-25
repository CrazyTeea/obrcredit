<?php

namespace app\models\app\students;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\students\StudentDocumentTypes;

/**
 * StudentDocumentTypesSearch represents the model behind the search form of `app\models\app\students\StudentDocumentTypes`.
 */
class StudentDocumentTypesSearch extends StudentDocumentTypes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'system_status'], 'integer'],
            [['descriptor', 'label', 'updated_at', 'created_at'], 'safe'],
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
        $query = StudentDocumentTypes::find();

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
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'system_status' => $this->system_status,
        ]);

        $query->andFilterWhere(['like', 'descriptor', $this->descriptor])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}

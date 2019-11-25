<?php

namespace app\models\app\students;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\students\StudentDocumentList;

/**
 * StudentDocumentListSearch represents the model behind the search form of `app\models\app\students\StudentDocumentList`.
 */
class StudentDocumentListSearch extends StudentDocumentList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_student', 'id_document_type', 'id_file', 'system_status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
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
        $query = StudentDocumentList::find();

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
            'id_student' => $this->id_student,
            'id_document_type' => $this->id_document_type,
            'id_file' => $this->id_file,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'system_status' => $this->system_status,
        ]);

        return $dataProvider;
    }
}

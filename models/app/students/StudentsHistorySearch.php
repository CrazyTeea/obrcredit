<?php

namespace app\models\app\students;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\students\StudentsHistory;

/**
 * StudentsHistorySearch represents the model behind the search form of `app\models\app\students\StudentsHistory`.
 */
class StudentsHistorySearch extends StudentsHistory
{
    public $id_number_pp;
    public $id_user_from;
    public $id_user_to;
    public $year;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_student', 'id_user_from', 'system_status', 'id_user_to'], 'integer'],
            [['changes', 'updated_at', 'created_at'], 'safe'],
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
        $query = StudentsHistory::find()->joinWith(['student']);
        if (!is_null($this->id_number_pp) and !is_null($this->year)){
            $query->where([Students::tableName().'.id_number_pp'=>$this->id_number_pp,'YEAR('.Students::tableName().'.date_start)'=>$this->year]);
        }
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
            Students::tableName().'.id' => $this->id,
            'id_student' => $this->id_student,
            'id_user_from' => $this->id_user_from,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'system_status' => $this->system_status,
            'id_user_to' => $this->id_user_to,
        ]);

        $query->andFilterWhere(['like', 'changes', $this->changes]);

        return $dataProvider;
    }
}

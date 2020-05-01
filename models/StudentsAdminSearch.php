<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentsAdmin;

/**
 * StudentsAdminSearch represents the model behind the search form of `app\models\StudentsAdmin`.
 */
class StudentsAdminSearch extends StudentsAdmin
{
    public $org_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_org', 'education_status', 'status', 'osnovanie', 'grace_period', 'id_number_pp', 'id_bank', 'perevod', 'isEnder', 'system_status', 'id_org_old'], 'integer'],
            [['name','org_name', 'code',
                'date_create', 'date_start_grace_period3',
                'date_end_grace_period3', 'date_credit',
                'date_status',
                'date_start_grace_period1',
                'date_end_grace_period1',
                'date_start_grace_period2',
                'date_end_grace_period2', 'date_start',
                'date_ender', 'old_code', 'date_act'], 'safe'],
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
        $query = StudentsAdmin::find()->joinWith(['org']);

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
            'students.id' => $this->id,
            'id_org' => $this->id_org,
            'education_status' => $this->education_status,
            'date_create' => $this->date_create,
            'status' => $this->status,
            'osnovanie' => $this->osnovanie,
            'grace_period' => $this->grace_period,
            'date_start_grace_period3' => $this->date_start_grace_period3,
            'date_end_grace_period3' => $this->date_end_grace_period3,
            'id_number_pp' => $this->id_number_pp,
            'id_bank' => $this->id_bank,
            'date_status' => $this->date_status,
            'date_start_grace_period1' => $this->date_start_grace_period1,
            'date_end_grace_period1' => $this->date_end_grace_period1,
            'date_start_grace_period2' => $this->date_start_grace_period2,
            'date_end_grace_period2' => $this->date_end_grace_period2,
            'date_start' => $this->date_start,
            'perevod' => $this->perevod,
            'isEnder' => $this->isEnder,
            'date_ender' => $this->date_ender,
            'students.system_status' => $this->system_status,
            'id_org_old' => $this->id_org_old,
            'date_act' => $this->date_act,
        ]);

        $query->andFilterWhere(['like', 'students.name', $this->name])
            ->andFilterWhere(['like','organizations.name',$this->org_name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'date_credit', $this->date_credit])
            ->andFilterWhere(['like', 'old_code', $this->old_code]);

        return $dataProvider;
    }
}

<?php

namespace app\models\app\students;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\students\Students;

/**
 * StudentsSearch represents the model behind the search form of `app\models\app\students\Students`.
 */
class StudentsSearch extends Students
{
    public $date_education_status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_org', 'education_status', 'status', 'osnovanie', 'grace_period'], 'integer'],
            [['name', 'code', 'date_education_status','date_create', 'date_start_grace_period', 'date_end_grace_period'], 'safe'],
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
        $query = Students::find()->joinWith(['organization','dateStatuses','numberPP','bank']);

       /* if ( User::$cans[2])
            $query->andWhere(['id_org'=>User::findIdentity(Yii::$app->getUser()->getId())->id_org]);*/

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
                'attributes'=>[
                    'name',
                    'code',
                    'education_status',
                    'grace_period',
                    'date_start_grace_period1',
                    'date_create',
                    'date_credit',
                    'date_status',
                    'bank'=>[
                        'asc' => ['banks.name' => SORT_ASC],
                        'desc' => ['banks.name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'numberPP'=>[
                        'asc' => ['numbers_pp.number' => SORT_ASC],
                        'desc' => ['numbers_pp.number' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'organization'=>[
                        'asc' => ['organizations.short_name' => SORT_ASC],
                        'desc' => ['organizations.short_name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'dateLastStatus'=>[
                        'asc' => ['dates_education_status.updated_at' => SORT_ASC],
                        'desc' => ['dates_education_status.updated_at' => SORT_DESC],
                        'default' => SORT_DESC
                    ]
                ]
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
            'id_org' => $this->id_org,
            'education_status' => $this->education_status,
            'date_last_status.updated_at' => $this->date_education_status,
            'date_create' => $this->date_create,
            'status' => $this->status,
            'osnovanie' => $this->osnovanie,
            'grace_period' => $this->grace_period,
            'date_start_grace_period1' => $this->date_start_grace_period1,
            'date_end_grace_period1' => $this->date_end_grace_period1,
            'date_start_grace_period2' => $this->date_start_grace_period2,
            'date_end_grace_period2' => $this->date_end_grace_period2,
            'date_start_grace_period3' => $this->date_start_grace_period3,
            'date_end_grace_period3' => $this->date_end_grace_period3,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}

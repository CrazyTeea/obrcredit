<?php

namespace app\models\app\students;



use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;


/**
 * StudentsSearch represents the model behind the search form of `app\models\app\students\Students`.
 */
class StudentsSearch extends Students
{
    public $date_education_status;
    public $month;
    public $year;
    public $org;
    public $cans;
    public $ender;
    public $osn;
    public $grace;
    public $a;
    public $d;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['month','id', 'id_org', 'education_status', 'status', 'osnovanie', 'grace_period','year'], 'integer'],
            [['name', 'code', 'date_education_status','date_create', 'date_start_grace_period', 'date_end_grace_period','org','osn'], 'safe'],
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
     * @param bool $all
     * @return ActiveDataProvider
     */
    public function search($params,$all = false)
    {
       // $subquery = DatesEducationStatus::find()->select(['MAX(updated_at)','id_student'])->groupBy(['id_student']);
        $query = Students::find()
            ->where(['students.system_status'=>1]);
        if (!$all) {
            if ( isset( $this->id_bank ) )
                $query->andWhere( ['id_bank' => $this->id_bank] );
            if ( isset( $this->month ) )
                $query->andWhere( ['MONTH(students.date_start)' => $this->month] );
            if ( isset( $this->year ) )
                $query->andWhere( ['YEAR(students.date_start)' => $this->year] );
            if (isset($this->id_number_pp)){
                $query->andWhere(['id_number_pp'=>$this->id_number_pp]);
            }
            if (isset($this->ender))
                $query->andWhere(['isEnder'=>1,'education_status'=>0]);
            else
                $query->andWhere(['isEnder'=>0]);
            if (isset($this->osn)) {
                $query->andWhere([ '>','osnovanie',0]);
            }
            else $query->andWhere(['or',['is','osnovanie',null],['osnovanie'=>0]]);

            if (isset($this->grace)){
                $query->andWhere(['or',['is not','grace_period',null],['<>','grace_period',0]]);
            } else $query->andWhere(['or',['is','grace_period',null],['grace_period'=>0]]);
            if ($this->a){
                $query->andWhere(['ext_status'=>1]);
            }elseif ($this->d){
                $query->andWhere(['ext_status'=>2]);
            }else{
                $query->andWhere(['ext_status'=>0]);
            }
        }


       /* if ( User::$cans[2])
            $query->andWhere(['id_org'=>User::findIdentity(Yii::$app->getUser()->getId())->id_org]);*/

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize' => 25
            ]
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

        $query->andFilterWhere(['like', 'students.name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);
        $query->groupBy(['date_credit'])->orderBy('name ASC');

        return $dataProvider;
    }
}

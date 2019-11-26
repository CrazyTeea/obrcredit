<?php

namespace app\models\app\students;

use app\models\app\students\Students;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['month','id', 'id_org', 'education_status', 'status', 'osnovanie', 'grace_period','year'], 'integer'],
            [['name', 'code', 'date_education_status','date_create', 'date_start_grace_period', 'date_end_grace_period','org'], 'safe'],
        ];
    }
    public function exportFields(){
        $cans = Yii::$app->session->get('cans');
        $exportColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','label'=>"ФИО обучающегося"],
            ['attribute'=>'organization','value'=>'organization.short_name','label'=>'Наименование ООВО'],
            ['attribute'=>'code','label'=>'Код направления подготовки'],
            ['attribute'=>'education_status','label'=>'Статус обучающегося','content'=>function($model){
                $os = mb_substr(Students::getOsnovanie()[ !empty($model->osnovanie) ? $model->osnovanie : 0  ],0,50);
                $data = "";
                switch ($model->osnovanie){
                    case 1:
                    case 2:
                    case 3:{
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:{
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:{
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:{$data = ""; break;}
                }
                $date = null;
                if (isset($model->dateLastStatus) and isset($model->dateLastStatus->date_end))
                    $date = Yii::$app->getFormatter()->asDate($model->dateLastStatus->date_end);

                $dta = ($date) ? "$date $data" : '';

                return $model->education_status ? $model->perevod ? 'Переведен на бюджет': "Обучается" : $dta;
            }],
            ['attribute'=>'grace_period','value'=>
                function($model){
                    $data = "";
                    switch ($model->grace_period){
                        case 1:{
                            $date = ($model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period1).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period1) : '';
                            $data = Students::getGracePeriod()[1] . "($date)";
                            break;
                        }
                        case 2:{
                            $date = ($model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period2).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period2) : '';
                            $data = Students::getGracePeriod()[2] . "($date)";
                            break;
                        }
                        case 3:{
                            $date = ($model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period3).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period3) : '';
                            $data = Students::getGracePeriod()[3] . "($date)";
                            break;
                        }
                        default: {$data = ''; break;}
                    }
                    return $data;
                }
                ,'label'=>'Пролонгация льготного периода'
            ],
            ['attribute'=>'date_credit','label'=>'Дата заключения кредитного договора',],
            ['attribute'=>'dateLastStatus','value'=>'dateLastStatus.updated_at','label'=>'Дата изменения данных'],
        ];
        if (!$cans[2]) {
            $exportColumns = ArrayHelper::merge( $exportColumns, [
                ['attribute' => 'numberPP','value' => 'numberPP.number', 'label' => 'Номер ПП по образовательному кредиту'],
                ['attribute' => 'bank','value'=>'bank.name', 'label' => 'Наименование банка или иной кредитной организации'],
                ['attribute' => 'date_status', 'format' => 'date', 'label' => 'Дата утверждения отчета'],
            ] );
        }
        return $exportColumns;
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
        $query = Students::find()->where(['students.system_status'=>1])->joinWith(['organization','dateStatuses','numberPP','bank']);
        if (!$all) {
            if ( !empty( $this->id_bank ) )
                $query->andWhere( ['id_bank' => $this->id_bank] );
            if ( !empty( $this->month ) )
                $query->andWhere( ['MONTH(students.date_start)' => $this->month] );
            if ( !empty( $this->year ) )
                $query->andWhere( ['YEAR(students.date_start)' => $this->year] );
            if (!empty($this->id_number_pp)){
                $query->andWhere(['id_number_pp'=>$this->id_number_pp]);
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

        return $dataProvider;
    }
}

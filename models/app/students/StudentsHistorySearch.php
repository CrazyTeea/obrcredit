<?php

namespace app\models\app\students;

use app\models\app\Organizations;
use kartik\select2\Select2;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use app\models\app\students\StudentsHistory;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * StudentsHistorySearch represents the model behind the search form of `app\models\app\students\StudentsHistory`.
 */
class StudentsHistorySearch extends StudentsHistory
{
    public $id_number_pp;
    public $id_user_from;
    public $id_user_to;
    public $year;
    public $period;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_student', 'id_user_from', 'system_status', 'id_user_to'], 'integer'],
            [['changes', 'updated_at', 'created_at','period'], 'safe'],
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
        if (isset($this->id_number_pp) and isset($this->year)){
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

    //    $query->andFilterWhere(['like', 'changes', $this->changes]);

        return $dataProvider;
    }
    public static function getColumns(){
        $orgs = Organizations::getOrgs();
        return [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'student.name','label'=>'ФИО<br>обучающегося','encodeLabel' => false],
            ['attribute'=>'student.code','label'=>'Код<br>направления','encodeLabel' => false],
            /*['attribute' => 'student.education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false,
                'content' => function ( $model ) {
                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->student->osnovanie ) ? $model->student->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->student->osnovanie ) {
                    case 1:
                    case 2:
                    case 3:
                    {
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:
                    {
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:
                    {
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:
                    {
                        $data = "";
                        break;
                    }
                }

                $date = null;
                if ( isset( $model->student->dateLastStatus ) and isset( $model->student->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->student->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';
                if ($model->student->isEnder)
                    return "<span class='label label-info'>Выпускник</span><br>".Yii::$app->formatter->asDate($model->student->date_ender);

                return ( $model->student->education_status ) ? $model->student->perevod ? "<span class='label label-info'>Переведен на бюджет</span>" : "<span class='label label-info'> Обучается</span>" : $dta;
            }
            ],*/
            ['attribute'=>'student.date_credit','label'=>'Дата заключения<br>кредитного договора','encodeLabel' => false],
            ['attribute'=>'student.numberPP.number','label'=>'Номер<br>пп','encodeLabel' => false],
            ['attribute'=>'student.bank.name','label'=>'Наименование<br>банка','encodeLabel' => false],
            ['attribute'=>'userFrom.username','label'=>'Первоначальная<br>организация','encodeLabel' => false,'value'=>function($model){
                if (isset($model->userFrom)) {
                    if (isset($model->student->organization))
                        return $model->student->organization->name . "(" . $model->userFrom->username . ")";
                    return  "Не известная организация(" . $model->userFrom->username . ")";
                }
                return '';
            }/*,'visible'=>!Yii::$app->user->can('podved')*/],
            ['attribute'=>'userTo.username','label'=>'Конечная<br>организация','encodeLabel' => false,'value'=>function($model){
                if (isset($model->userTo)) {
                    if (isset($model->userTo->organization))
                        return $model->userTo->organization->name . "(" . $model->userTo->username . ")";
                    return  "Не известная организация(" . $model->userTo->username . ")";
                }
                return '';
            }],

            'change.change',

            ['attribute'=>'period','content'=>function($model){
                $sql = "SELECT minV.minV,maxV.maxV FROM 
                      (SELECT MIN(date_start) minV from students where name='{$model->student->name}' and code = '{$model->student->code}' and date_credit = '{$model->student->date_credit}') as minV,
                      (SELECT MAX(date_start) maxV from students where name='{$model->student->name}' and code = '{$model->student->code}' and date_credit = '{$model->student->date_credit}') as maxV";
                $q = Yii::$app->db->createCommand($sql)->queryOne();
                return Yii::$app->formatter->asDate($q['minV']).' - '.Yii::$app->formatter->asDate($q['maxV']);
            },'label'=>'Период'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{add}',
                'buttons'=>[
                    'add'=>function ($url, $model, $key) use ($orgs) {
                            $btn = "<a href='$url' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-plus'></span></a>";
                        if (!Yii::$app->session->get('cans')[2]) {

                            $btn = "
<!-- Button trigger modal -->
    <a  class='glyphicon glyphicon-plus' data-toggle='modal' data-target='#myModal_$model->id' style='margin-bottom: 5px'>
    </a>

    <!-- Modal -->
    <div class='modal fade' id='myModal_$model->id' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
            <form method='post' action='$url'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <h4 class='modal-title' id='myModalLabel'>Отправить в журнал</h4>
                </div>
                <div class='modal-body'>"
                                .Select2::widget([
                                    'name' => 'id_org',
                                    'data' => $orgs,
                                ]).
                    "
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Закрыть</button>
                    <button type='submit' class='btn btn-primary'>Отправить</button>
                </div>
            </form>
            </div>
        </div>
    </div>
";
                        }
                        return $btn;
                    },
                    'view'=>function ($url, $model, $key) {
                        $u = Url::to(['app/students/view','id'=>$model->student->id]);
                        return "<a href='$u' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    }
                ],
                'visibleButtons'=>[
                    'add'=>
                        function ($model, $key, $index) {
                            return $model->userTo ? false : true;
                        }
                ]
            ],
        ];
    }
}

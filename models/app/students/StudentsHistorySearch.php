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
    public $month;
    public $period;
    public $id_bank;
    public $org;
    public $org_old;
    public $student_name;
    public $student_code;
    public $student_credit;
    public $student_number;
    public $student_bank;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_student', 'id_user_from', 'system_status', 'id_user_to','student_number','org_old'], 'integer'],
            [['changes', 'updated_at', 'created_at','period'], 'safe'],
            [['org','student_name','student_bank','student_code','student_credit'],'string']
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
        if (isset($this->id_number_pp))
            $query->andWhere(['students.id_number_pp'=>$this->id_number_pp]);
        if (isset($this->month))
            $query->andWhere(['month(students.date_start)'=>$this->month]);
        if (isset($this->year))
            $query->andWhere(['year(students.date_start)'=>$this->year]);
        if (isset($this->id_bank))
            $query->andWhere(['students.id_bank'=>$this->id_bank]);
        if ($this->org_old)
            $query->andWhere([Students::tableName().'.id_org'=>$this->org_old]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => [
                'student.name'=>[
                    'asc' => ['students.name' => SORT_ASC],
                    'desc' => ['students.name' => SORT_DESC],
                ],
                'student.code'=>[
                    'asc' => ['students.code' => SORT_ASC],
                    'desc' => ['students.code' => SORT_DESC],
                ],
                'student.date_credit'=>[
                    'asc' => ['students.date_credit' => SORT_ASC],
                    'desc' => ['students.date_credit' => SORT_DESC],
                ],


            ]
            ]
        ]);

        $this->load($params);
        $ids = $this->id;
        $ids_q = Students::find()->select(['students.id']);
        if (isset($this->org)){
            $ids_q->joinWith(['organization'])->where(['like','organizations.name',$this->org]);
        }
        if (isset($this->student_bank)){
            $ids_q->joinWith(['bank'])->andWhere(['like','banks.name',$this->student_bank]);
        }
        if (isset($this->student_number)){
            $ids_q->joinWith(['numberPP'])->andWhere(['like','numbers_pp.number',$this->student_number]);
        }
        if (isset($this->student_number) || isset($this->student_bank) || isset($this->org)){
            $ids = $ids_q->column();
        }



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            Students::tableName().'.id' => $ids,
        ]);
        $query->andFilterWhere([
            'like', Students::tableName().'.name', $this->student_name,
        ]);
        $query->andFilterWhere([
            'like', Students::tableName().'.code', $this->student_code,
        ]);
        $query->andFilterWhere([
            'like', Students::tableName().'.date_credit', $this->student_credit,
        ]);



    //    $query->andFilterWhere(['like', 'changes', $this->changes]);

        return $dataProvider;
    }
    public static function getColumns(bool $export = false){
        $orgs = Organizations::getOrgs();
        $ret = null;
        if (!$export){
            $ret = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute'=>'student_name','value'=>'student.name','label'=>'ФИО<br>обучающегося','encodeLabel' => false],
                ['attribute'=>'student_code','value'=>'student.code','label'=>'Код<br>направления','encodeLabel' => false],
                ['attribute'=>'student_credit','value'=>'student.date_credit','label'=>'Дата заключения<br>кредитного договора','encodeLabel' => false],
                ['attribute'=>'student_number','value'=>'student.numberPP.number','label'=>'Номер<br>пп','encodeLabel' => false],
                ['attribute'=>'student_bank','value'=>'student.bank.name','label'=>'Наименование<br>банка','encodeLabel' => false],
                ['attribute'=>'org','label'=>'Первоначальная<br>организация','encodeLabel' => false,'value'=>function($model){

                    if (isset($model->student->oldOrganization))
                        return $model->student->oldOrganization->name;
                    elseif(isset($model->student->organization))
                        return $model->student->organization->name;
                    else
                        return  "Неизвестная организация";
                }],
                ['attribute'=>'userTo.username','label'=>'Конечная<br>организация','encodeLabel' => false,'value'=>function($model){
                    if (isset($model->userTo)) {
                        if (isset($model->student->organization) and isset($model->student->oldOrganization))
                            return $model->student->organization->name . "(" . $model->userTo->username . ")";
                        return  "Неизвестная организация(" . $model->userTo->username . ")";
                    }
                    return '';
                }],

                'change.change',

                ['attribute'=>'period','filter'=>false,'content'=>function($model){
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
                                    .Html::hiddenInput(Yii::$app->request->csrfParam,Yii::$app->request->getCsrfToken())
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
                                return !$model->userTo and Yii::$app->user->can('user');
                            }
                    ]
                ],
            ];
        }
        else{
            $ret = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute'=>'student_name','value'=>'student.name','label'=>'ФИО обучающегося'],
                ['attribute'=>'student_code','value'=>'student.code','label'=>'Код направления'],
                ['attribute'=>'student_credit','value'=>'student.date_credit','label'=>'Дата заключения кредитного договора'],
                ['attribute'=>'student_number','value'=>'student.numberPP.number','label'=>'Номер пп'],
                ['attribute'=>'student_bank','value'=>'student.bank.name','label'=>'Наименование банка'],
                ['attribute'=>'org','label'=>'Первоначальная организация','value'=>function($model){

                    if (isset($model->student->oldOrganization))
                        return $model->student->oldOrganization->name;
                    elseif(isset($model->student->organization))
                        return $model->student->organization->name;
                    else
                        return  "Неизвестная организация";
                }],
                ['attribute'=>'userTo.username','label'=>'Конечная организация','value'=>function($model){
                    if (isset($model->userTo)) {
                        if (isset($model->student->organization) and isset($model->student->oldOrganization))
                            return $model->student->organization->name . "(" . $model->userTo->username . ")";
                        return  "Неизвестная организация(" . $model->userTo->username . ")";
                    }
                    return '';
                }],

                'change.change',

                ['attribute'=>'period','filter'=>false,'content'=>function($model){
                    $sql = "SELECT minV.minV,maxV.maxV FROM 
                      (SELECT MIN(date_start) minV from students where name='{$model->student->name}' and code = '{$model->student->code}' and date_credit = '{$model->student->date_credit}') as minV,
                      (SELECT MAX(date_start) maxV from students where name='{$model->student->name}' and code = '{$model->student->code}' and date_credit = '{$model->student->date_credit}') as maxV";
                    $q = Yii::$app->db->createCommand($sql)->queryOne();
                    return Yii::$app->formatter->asDate($q['minV']).' - '.Yii::$app->formatter->asDate($q['maxV']);
                },'label'=>'Период'],

            ];
        }
        return $ret;
    }
}

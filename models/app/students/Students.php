<?php

namespace app\models\app\students;

use app\models\app\Banks;
use app\models\app\Files;
use app\models\app\Organizations;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property int $system_status
 * @property string $name
 * @property int $id_org
 * @property string $code
 * @property string $old_code
 * @property int $education_status
 * @property string $date_create
 * @property int $status
 * @property int $osnovanie
 * @property int $grace_period
 * @property string $date_start_grace_period
 * @property string $date_end_grace_period
 * @property string $date_start_grace_period1
 * @property string $date_end_grace_period1
 * @property string $date_start_grace_period2
 * @property string $date_end_grace_period2
 * @property string $date_start_grace_period3
 * @property string $date_end_grace_period3
 * @property string $date_credit
 * @property int $id_number_pp
 * @property string $date_status
 * @property int $id_bank
 * @property int $perevod
 * @property int $id_org_old
 * @property string $date_start
 * @property string $date_ender
 * @property string $date_act
 * @property boolean $isEnder
 * @property integer $ext_status
 *
 * @var StudentDocs $docs
 *
 * @property DatesEducationStatus $dateLastStatus
 * @property DatesEducationStatus[] $dateStatuses
 *
 *
 */
class Students extends ActiveRecord
{
    public $count,
        $month,$year,$bank_name;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            /* [['rasp_act4'],'file','skipOnEmpty'=>false,'when'=>function($model)
             {
                 if ($model->perevod)
                     return true;
                 return false;
             },'uploadRequired'=>'При переводе на бюджет требуется загрузить файл'],*/
            [['id_org', 'education_status', 'status', 'osnovanie','ext_status',
                'grace_period','id_number_pp','id_bank','perevod','id_org_old'], 'integer'],
            [[ 'date_create','date_start',
                'date_start_grace_period1', 'date_end_grace_period1',
                'date_start_grace_period2', 'date_end_grace_period2',
                'date_start_grace_period3', 'date_end_grace_period3',
                'date_credit','date_status','date_act'], 'safe'],
            [['name', 'code','old_code'], 'string', 'max' => 255],
            [['isEnder'],'boolean'],
            [['date_ender'],'required','when'=>function($model){
                return $model->isEnder ? true : false;
            }],
            [['date_start'],'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО обучающегося',
            'id_org' => 'Id Org',
            'code' => 'Код направления',
            'education_status' => 'Статус обучающегося',
            'date_education_status' => 'Дата изменения статуса',
            'date_create' => 'Дата добавления',
            'status' => 'Статус отчета',
            'osnovanie' => 'Основание',
            'grace_period' => 'Отсрочка льготного периода',
            'date_start_grace_period1' => 'Начало',
            'date_end_grace_period1' => 'Конец',
            'date_start_grace_period2' => 'Начало',
            'date_end_grace_period2' => 'Конец',
            'date_start_grace_period3' => 'Начало',
            'date_end_grace_period3' => 'Конец',
            'date_credit'=>'Дата заключения кредитного договора',
            'id_number_pp'=>'Номер ПП по образовательному кредиту',
            'id_bank'=>'Наименование банка или иной кредитной организации',
            'date_status'=>'Дата утверждения отчета',
            'isEnder'=>'Выпускник',
            'date_ender'=>'Дата выпуска',
            'date_start'=>'Месяц(дата)',
            'date_act'=>'Дата распределительного акта'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization(){
        return $this->hasOne(Organizations::className(),['id'=>'id_org']);
    }
    public function getOldOrganization(){
        return $this->hasOne(Organizations::className(),['id'=>'id_org_old']);
    }

    /**
     * @return array
     */
    public static function getOsnovanie(){
        return [
            '',
            'отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по профессиональной образовательной программе обязанностей по добросовестному освоению такой образовательной программы и выполнению учебного плана',
            'установление нарушения порядка приема в образовательную организацию, повлекшего по вине обучающегося его незаконное зачисление в образовательную организацию',
            'отчислен по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося',
            'в связи с ликвидацией образовательной организации',
            'по независящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации',
            'обучающимся (заемщиком) принято решение об отказе от продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации, в том числе в случае ликвидации образовательной организации'
        ];
    }

    /**
     * @return array
     */
    public static function getGracePeriod(){
        return[
            '',
          'академический отпуск',
          'отпуск по беременности и родам',
          'отпуск по уходу за ребенком по достижении им 3-х лет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateLastStatus(){
        return $this->hasOne(DatesEducationStatus::className(),['id_student'=>'id'])->orderBy(['updated_at'=>SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateStatuses(){
        return $this->hasMany(DatesEducationStatus::className(),['id_student'=>'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocs(){
        return $this->hasMany(StudentDocumentList::className(),['id_student'=>'id']);
    }

    public function getCount($year,$month,$id_bank,$id_number_pp,$attr = false, $val=0){
        $q= self::find()->where(['system_status'=>1,'YEAR(date_start)' => $year,'MONTH(date_start)' => $month,'id_bank'=>$id_bank,'id_number_pp'=>$id_number_pp]);
        if ($attr)
            $q->andWhere(["$attr"=>$val]);
        return $q->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Banks::className(),['id'=>'id_bank']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNumberPP(){
        return $this->hasOne(NumbersPp::className(),['id'=>'id_number_pp']);
    }

    public static function getColumns(bool $export){
        $ret = null;
        if ($export) {
            $ret = [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                ['attribute' => 'name', 'label' => "ФИО обучающегося", 'encodeLabel' => false],
                ['attribute' => 'organization', 'value' => 'organization.name', 'label' => 'Наименование ООВО', 'encodeLabel' => false],
                ['attribute' => 'code', 'label' => 'Код направления подготовки', 'encodeLabel' => false],
                ['attribute' => 'old_code', 'label' => 'Старый код направления подготовки', 'encodeLabel' => false],
                ['attribute' => 'education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false, 'content' => function ($model) {
                    $os = mb_substr(Students::getOsnovanie()[!empty($model->osnovanie) ? $model->osnovanie : 0], 0, 50);
                    $data = "";
                    switch ($model->osnovanie) {
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


                    //$dta = $data;
                    if ($model->isEnder)
                        return "Выпускник" ;

                    if (!$model->system_status)
                        return 'Не найден';

                    return ($model->education_status) ? ($l = ($model->perevod) ? "Переведен на бюджет" : "Обучается") : " {$data}";
                }],
                ['attribute' => 'grace_period', 'encodeLabel' => false, 'value' =>
                    function ($model) {
                        $data = "";
                        switch ($model->grace_period) {
                            case 1:
                            {
                                $data = Students::getGracePeriod()[1] ;
                                break;
                            }
                            case 2:
                            {
                                $data = Students::getGracePeriod()[2] ;
                                break;
                            }
                            case 3:
                            {

                                $data = Students::getGracePeriod()[3] ;
                                break;
                            }
                            default:
                            {
                                $data = '';
                                break;
                            }
                        }
                        return $data;
                    }
                    , 'label' => 'Пролонгация льготного периода'
                ],
                ['attribute' => 'date_credit', 'encodeLabel' => false, 'label' => 'Дата заключения кредитного договора',],
                //['attribute' => 'dateLastStatus', 'encodeLabel' => false, 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата изменения данных'],
            ];
            if (!Yii::$app->user->can('podved')){
                $ret = ArrayHelper::merge( $ret, [
                    ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'encodeLabel' => false, 'label' => 'Номер ПП по образовательному кредиту'],
                    ['attribute' => 'bank', 'value' => 'bank.name', 'encodeLabel' => false, 'label' => 'Наименование банка или иной кредитной организации'],
                    //['attribute' => 'date_status', 'encodeLabel' => false, 'format' => 'date', 'label' => 'Дата утверждения отчета'],
                ] );
            }
        }else {
            $ret = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'name', 'label' => "ФИО <br> обучающегося", 'encodeLabel' => false],
                ['attribute' => 'organization', 'value' => 'organization.name', 'label' => 'Наименование <br> ООВО', 'encodeLabel' => false],
                ['attribute' => 'code', 'label' => 'Код <br> направления <br> подготовки', 'encodeLabel' => false],
                ['attribute' => 'education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false, 'content' => function ($model) {
                    $os = mb_substr(Students::getOsnovanie()[!empty($model->osnovanie) ? $model->osnovanie : 0], 0, 50);
                    $data = "";
                    switch ($model->osnovanie) {
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
                    if (isset($model->dateLastStatus) and isset($model->dateLastStatus->date_end))
                        $date = Yii::$app->getFormatter()->asDate($model->dateLastStatus->date_end);

                    $dta = ($date) ? "$date $data" :  $data;
                    if ($model->isEnder)
                        return "<span class='label label-info'>Выпускник</span><br>" . Yii::$app->formatter->asDate($model->date_ender);

                    if ($model->osnovanie)
                        return "<span class='label label-info'>Отчислен</span><br> $data";

                    if ($model->perevod)
                        return "<span class='label label-info'>Переведен на бюджет</span>";
                    if ($model->education_status || $model->grace_period)
                        return "<span class='label label-info'> Обучается</span>";


                  //  return ($model->education_status) ? ($l = ($model->perevod) ? "<span class='label label-info'>Переведен на бюджет</span>" : "<span class='label label-info'> Обучается</span>") : "$dta";
                }
                ],
                ['attribute' => 'grace_period', 'encodeLabel' => false, 'value' =>
                    function ($model) {
                        $data = "";
                        switch ($model->grace_period) {
                            case 1:
                            {
                                $date = ($model->date_start_grace_period1 and $model->date_end_grace_period1) ?
                                    Yii::$app->getFormatter()->asDate($model->date_start_grace_period1) . '-' . Yii::$app->getFormatter()->asDate($model->date_end_grace_period1) : '';
                                $data = Students::getGracePeriod()[1] . "($date)";
                                break;
                            }
                            case 2:
                            {
                                $date = ($model->date_start_grace_period2 and $model->date_end_grace_period2) ?
                                    Yii::$app->getFormatter()->asDate($model->date_start_grace_period2) . '-' . Yii::$app->getFormatter()->asDate($model->date_end_grace_period2) : '';
                                $data = Students::getGracePeriod()[2] . "($date)";
                                break;
                            }
                            case 3:
                            {
                                $date = ($model->date_start_grace_period3 and $model->date_end_grace_period3) ?
                                    Yii::$app->getFormatter()->asDate($model->date_start_grace_period3) . '-' . Yii::$app->getFormatter()->asDate($model->date_end_grace_period3) : '';
                                $data = Students::getGracePeriod()[3] . "($date)";
                                break;
                            }
                            default:
                            {
                                $data = '';
                                break;
                            }
                        }
                        return $data;
                    }
                    , 'label' => 'Пролонгация<br>льготного<br>периода'
                ],
                ['attribute' => 'date_credit', 'encodeLabel' => false, 'label' => 'Дата <br> заключения <br> кредитного <br> договора',],
                ['attribute' => 'dateLastStatus', 'encodeLabel' => false, 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата <br> изменения <br> данных'],
                ['attribute' => 'date_status', 'encodeLabel' => false, 'format' => 'date', 'label' => 'Дата <br> утверждения <br> отчета'],
            ];
            if (!Yii::$app->user->can('podved')){
                $ret = ArrayHelper::merge( $ret, [
                    ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'encodeLabel' => false, 'label' => 'Номер <br> ПП <br> по <br> образовательному <br>кредиту'],
                    ['attribute' => 'bank', 'value' => 'bank.name', 'encodeLabel' => false, 'label' => 'Наименование <br> банка <br>или<br> иной <br> кредитной <br>организации'],

                ] );
            }
        }
        return $ret;

    }

    /**
     * @param Students $student
     * @param Files $file
     * @param array $studentDocTypes
     * @return bool
     * @throws \yii\base\Exception
     */
    public function addStudentDocs(Files $file, array $studentDocTypes){

        $done = true;
        foreach ($studentDocTypes as $studentDocType){
            $instance = UploadedFile::getInstance($file,"[$studentDocType->descriptor]file");
            if ($instance){
                $studentDoc = new StudentDocumentList();
                if (!$studentDoc->add($file,$instance,$this,$studentDocType->id)){
                    $done=false;
                    break;
                }
            }
        }
        return $done;
    }
    public function deleteDocument(string $descriptor)
    {
        $descriptor = StudentDocumentTypes::findOne(['descriptor'=>$descriptor]);
        if ($descriptor) {
            $doc = StudentDocumentList::findOne( ['id_student' => $this->id, 'id_document_type' => $descriptor->id] );
            if ($doc){
                if ($doc->file) $doc->file->delete($this);
                $doc->delete();
            }
        }
    }

}

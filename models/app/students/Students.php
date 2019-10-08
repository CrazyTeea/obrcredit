<?php

namespace app\models\app\students;

use app\models\app\Banks;
use app\models\app\Organizations;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $name
 * @property int $id_org
 * @property string $code
 * @property int $education_status
 * @property string $date_create
 * @property int $status
 * @property int $osnovanie
 * @property int $grace_period
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
    public $rasp_act0,
        $rasp_act1,
        $rasp_act2,
        $rasp_act3,
        $dogovor,
        $rasp_act_otch;


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
            [['rasp_act0','rasp_act1','rasp_act2','rasp_act3','dogovor','rasp_act_otch'],'file'],
            [['id_org', 'education_status', 'status', 'osnovanie', 'grace_period','id_number_pp','id_bank'], 'integer'],
            [[ 'date_create',
                'date_start_grace_period1', 'date_end_grace_period1',
                'date_start_grace_period2', 'date_end_grace_period2',
                'date_start_grace_period3', 'date_end_grace_period3',
                'date_credit','date_status'], 'safe'],
            [['name', 'code',], 'string', 'max' => 255],
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
            'date_status'=>'Дата утверждения отчета'
        ];
    }
    public function getOrganization(){
        return $this->hasOne(Organizations::className(),['id'=>'id_org']);
    }
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
    public static function getGracePeriod(){
        return[
            '',
          'академический отпуск',
          'отпуск по беременности и родам',
          'отпуск по уходу за ребенком по достижении им 3-х лет',
        ];
    }
    public function getDateLastStatus(){
        return $this->hasOne(DatesEducationStatus::className(),['id_student'=>'id'])->orderBy(['updated_at'=>SORT_DESC]);
    }
    public function getDateStatuses(){
        return $this->hasMany(DatesEducationStatus::className(),['id_student'=>'id']);
    }
    public function getDocs()
    {
        return $this->hasMany(StudentDocs::className(),['id_student'=>'id']);
    }
    public function getBank()
    {
        return $this->hasOne(Banks::className(),['id'=>'id_bank']);
    }
    public function getNumberPP(){
        return $this->hasOne(NumbersPp::className(),['id'=>'id_number_pp']);
    }
}

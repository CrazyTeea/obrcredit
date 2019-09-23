<?php

namespace app\models\app\students;

use app\models\app\Organizations;
use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $name
 * @property int $id_org
 * @property string $code
 * @property int $education_status
 * @property string $date_education_status
 * @property string $date_create
 * @property int $status
 * @property int $osnovanie
 * @property int $grace_period
 * @property string $date_start_grace_period
 * @property string $date_end_grace_period
 */
class Students extends \yii\db\ActiveRecord
{
    public $rasp_act0,
        $rasp_act1,
        $rasp_act2,
        $rasp_act3,
        $dogovor,
        $rasp_act_otch;
    /**
     * @var StudentDocs $docs
     */
    public $docs;
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
            [['id_org', 'education_status', 'status', 'osnovanie', 'grace_period'], 'integer'],
            [['date_education_status', 'date_create', 'date_start_grace_period', 'date_end_grace_period'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'id_org' => 'Id Org',
            'code' => 'Код направления',
            'education_status' => ' Статус обучающегося',
            'date_education_status' => 'Date Education Status',
            'date_create' => 'Дата добавления',
            'status' => 'Статус отчета',
            'osnovanie' => 'Osnovanie',
            'grace_period' => 'Grace Period',
            'date_start_grace_period' => 'Начало',
            'date_end_grace_period' => 'Конец',
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
    public function getDocs()
    {
        return $this->hasMany(StudentDocs::className(),['id_student'=>'id']);
    }
}

<?php

namespace app\models;

use app\models\app\Banks;
use app\models\app\Organizations;
use app\models\app\students\DatesEducationStatus;
use app\models\app\students\NumbersPp;
use app\models\app\students\StudentDocumentList;
use app\models\app\students\StudentsHistory;
use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $id_org
 * @property string|null $code
 * @property int|null $education_status
 * @property string|null $date_create
 * @property int|null $status
 * @property int|null $osnovanie
 * @property int|null $grace_period
 * @property string|null $date_start_grace_period3
 * @property string|null $date_end_grace_period3
 * @property string|null $date_credit
 * @property int|null $id_number_pp
 * @property int|null $id_bank
 * @property string|null $date_status
 * @property string|null $date_start_grace_period1
 * @property string|null $date_end_grace_period1
 * @property string|null $date_start_grace_period2
 * @property string|null $date_end_grace_period2
 * @property string|null $date_start
 * @property int|null $perevod
 * @property int|null $isEnder
 * @property string|null $date_ender
 * @property int|null $system_status
 * @property string|null $old_code
 * @property int|null $id_org_old
 * @property string|null $date_act
 *
 * @property DatesEducationStatus[] $datesEducationStatuses
 * @property StudentDocumentList[] $studentDocumentLists
 * @property Organizations $org
 * @property Banks $bank
 * @property NumbersPp $numberPp
 * @property Organizations $orgOld
 * @property StudentsHistory[] $studentsHistories
 */
class StudentsAdmin extends \yii\db\ActiveRecord
{
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
            [['id_org', 'education_status', 'status', 'osnovanie', 'grace_period', 'id_number_pp', 'id_bank', 'perevod', 'isEnder', 'system_status', 'id_org_old'], 'integer'],
            [['date_create', 'date_start_grace_period3', 'date_end_grace_period3', 'date_status', 'date_start_grace_period1', 'date_end_grace_period1', 'date_start_grace_period2', 'date_end_grace_period2', 'date_start', 'date_ender', 'date_act'], 'safe'],
            [['name', 'code', 'date_credit', 'old_code'], 'string', 'max' => 255],
            [['id_org','name','date_credit'],'required'],
            [['id_org'], 'exist', 'skipOnError' => true, 'targetClass' => Organizations::class, 'targetAttribute' => ['id_org' => 'id']],
            [['id_bank'], 'exist', 'skipOnError' => true, 'targetClass' => Banks::class, 'targetAttribute' => ['id_bank' => 'id']],
            [['id_number_pp'], 'exist', 'skipOnError' => true, 'targetClass' => NumbersPp::class, 'targetAttribute' => ['id_number_pp' => 'id']],
            [['id_org_old'], 'exist', 'skipOnError' => true, 'targetClass' => Organizations::class, 'targetAttribute' => ['id_org_old' => 'id']],
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
            'id_org' => 'Id организации',
            'code' => 'код',
            'education_status' => 'статус обучения',
            'date_create' => 'Дата создания',
            'status' => 'Статус',
            'osnovanie' => 'Основание отчисления',
            'grace_period' => 'Основание академа',
            'date_start_grace_period3' => 'Date Start Grace Period3',
            'date_end_grace_period3' => 'Date End Grace Period3',
            'date_credit' => 'Кредит',
            'id_number_pp' => 'Id пп',
            'id_bank' => 'Id банка',
            'date_status' => 'Date Status',
            'date_start_grace_period1' => 'Date Start Grace Period1',
            'date_end_grace_period1' => 'Date End Grace Period1',
            'date_start_grace_period2' => 'Date Start Grace Period2',
            'date_end_grace_period2' => 'Date End Grace Period2',
            'date_start' => 'Главная дата',
            'perevod' => 'перевод на бюджет',
            'isEnder' => 'выпускник',
            'date_ender' => 'дата выпуска',
            'system_status' => 'Системный статус',
            'old_code' => 'Старый код',
            'id_org_old' => 'старый Id организации',
            'date_act' => 'Date Act',
        ];
    }

    /**
     * Gets query for [[DatesEducationStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatesEducationStatuses()
    {
        return $this->hasMany(DatesEducationStatus::className(), ['id_student' => 'id']);
    }

    /**
     * Gets query for [[StudentDocumentLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentDocumentLists()
    {
        return $this->hasMany(StudentDocumentList::className(), ['id_student' => 'id']);
    }

    /**
     * Gets query for [[Org]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organizations::className(), ['id' => 'id_org']);
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Banks::className(), ['id' => 'id_bank']);
    }

    /**
     * Gets query for [[NumberPp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNumberPp()
    {
        return $this->hasOne(NumbersPp::className(), ['id' => 'id_number_pp']);
    }

    /**
     * Gets query for [[OrgOld]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgOld()
    {
        return $this->hasOne(Organizations::className(), ['id' => 'id_org_old']);
    }

    /**
     * Gets query for [[StudentsHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentsHistories()
    {
        return $this->hasMany(StudentsHistory::className(), ['id_student' => 'id']);
    }
}

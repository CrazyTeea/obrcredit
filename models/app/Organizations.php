<?php

namespace app\models\app;

use app\models\app\students\Students;
use app\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "organizations".
 *
 * @property int $id
 * @property int $system_status
 * @property string $name
 * @property string $short_name
 * @property string $full_name
 */
class Organizations extends ActiveRecord
{
    public $student_status;

    public static function className()
    {
        return get_called_class();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_status'],'integer'],
            [['name', 'short_name', 'full_name'], 'string', 'max' => 500],
        ];
    }
    public function getUsers(){
        return $this->hasMany(User::className(),['id_org'=>'id']);
    }
    public function getUsersCount(){
        return $this->hasMany(User::className(),['id_org'=>'id'])->count();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'short_name' => 'Краткое Наименование',
            'full_name' => 'Полное Наименование',
            'usersCount'=>'Кол-во пользователей'
        ];
    }
    public function getStudents(){
        return $this->hasMany(Students::className(),['id_org'=>'id']);
    }

    public static function getOrgs(){
        return ArrayHelper::map(self::find()->select(['id','name','system_status'])->where(['system_status'=>1])->all(),'id','name');
    }
}

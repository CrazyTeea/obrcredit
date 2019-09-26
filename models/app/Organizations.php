<?php

namespace app\models\app;

use app\models\app\students\Students;
use Yii;
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
class Organizations extends \yii\db\ActiveRecord
{
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
        ];
    }
    public function getStudents(){
        return $this->hasMany(Students::className(),['id_org'=>'id']);
    }
    public function getStudentsCount(){
        return $this->hasMany(Students::className(),['id_org'=>'id'])->where(['status'=>1])->count();
    }
    public static function getOrgs(){
        return ArrayHelper::map(self::find()->select(['id','short_name'])->all(),'id','short_name');
    }
}

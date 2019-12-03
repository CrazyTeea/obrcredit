<?php

namespace app\models\app\students;

use app\models\User;
use Yii;

/**
 * This is the model class for table "students_history".
 *
 * @property int $id
 * @property int|null $id_student
 * @property int|null $id_user_from
 * @property string|null $changes
 * @property string $updated_at
 * @property string $created_at
 * @property int|null $system_status
 * @property int|null $id_user_to
 */
class StudentsHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_student', 'id_user_from', 'system_status', 'id_user_to'], 'integer'],
            [['changes'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_student' => 'Id Student',
            'id_user_from' => 'Id User From',
            'changes' => 'Changes',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
            'id_user_to' => 'Id User To',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent(){
        return $this->hasOne(Students::class,['id'=>'id_student']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom(){
        return $this->hasOne(User::class,['id'=>'id_user_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo(){
        return $this->hasOne(User::class,['id'=>'id_user_to']);
    }
}

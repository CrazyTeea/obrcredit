<?php

namespace app\models\app\students;

use app\models\app\Files;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "student_document_list".
 *
 * @property int $id
 * @property int $id_student
 * @property int $id_document_type
 * @property int $id_file
 * @property string $updated_at
 * @property string $created_at
 * @property int $system_status
 */
class StudentDocumentList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_document_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_student', 'id_document_type', 'id_file', 'system_status'], 'integer'],
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
            'id_document_type' => 'Id Document Type',
            'id_file' => 'Id File',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
        ];
    }
    public function getFile(){
        return $this->hasOne(Files::className(),['id'=>'id_file']);
    }
    public function getType(){
        return $this->hasOne(StudentDocumentTypes::className(),['id'=>'id_document_type']);
    }

    /**
     * @param Files $file
     * @param UploadedFile $instance
     * @param int $id_student
     * @param int $id_type
     * @return bool
     * @throws \yii\base\Exception
     */
    public function add( Files $file, UploadedFile $instance, Students $student, int $id_type){
        $this->id_student = $student->id;
        $this->id_file = $file->upload($instance,$student);
        $this->id_document_type = $id_type;
        return $this->id_file and $this->save();
    }
}

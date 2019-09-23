<?php

namespace app\models\app\students;

use crazyteea\beautyfiles\models\Descriptions;
use crazyteea\beautyfiles\models\Files;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "student_docs".
 *
 * @property int $id
 * @property int $id_file
 * @property int $id_descriptor
 * @property int $id_student
 */
class StudentDocs extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_docs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['rasp_act0','rasp_act1','rasp_act2','rasp_act3','dogovor','rasp_act_otch'],'file'],
            [['id_file', 'id_descriptor','id_student'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_file' => 'Id File',
            'id_descriptor' => 'Id Descriptor',
            'id_student'=>'student'
        ];
    }
    public function getFile(){
        return $this->hasOne(Files::className(),['id'=>'id_file']);
    }
    public function getDescriptor(){
        return $this->hasOne(Descriptions::className(),['id'=>'id_descriptor']);
    }
    public static function getDocByDescriptorId($id,$id_student){
        return self::findOne(['id_descriptor'=>$id,'id_student'=>$id_student]);
    }
    public static function getDocByDescriptorName($desc,$id_student){
        $desc = Descriptions::findOne(['name'=>$desc]);
        if (!$desc)
            return null;
        return self::findOne(['id_descriptor'=>$desc->id,'id_student'=>$id_student]);

    }
    public static function download($id){
        $doc = self::findOne($id);
        $student = Students::findOne($doc->id_student);

        return $doc->file->downloadFile("/$student->id_org/$student->id/");
    }

    /**
     * @param $model
     * @param $path
     * @param $descriptor
     * @return bool
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public static function addDoc($model, $path, $descriptor){
        $doc = new StudentDocs();
        $doc->id_student = $model->id;
        $desc = Descriptions::findOne(['name'=>$descriptor]);
        if ($desc) {
           // var_dump($desc);exit();
            $file = new Files();
            $doc->id_descriptor = $desc->id;
            if ($file->uploadFile($path,$model,$descriptor)) {
                $doc->id_file=$file->id;
                if ($doc->save())
                    return true;
            }
            return false;
        }
        throw new NotFoundHttpException("Не найден десриптор файла: $descriptor");
    }
}

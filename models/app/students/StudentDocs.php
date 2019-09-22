<?php

namespace app\models\app\students;

use crazyteea\beautyfiles\models\Descriptions;
use crazyteea\beautyfiles\models\Files;
use Yii;

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
        return 'student_docs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rasp_act0','rasp_act1','rasp_act2','rasp_act3','dogovor','rasp_act_otch'],'file'],
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
}

<?php

namespace app\models\console;

use app\models\console\Files;
use Yii;
use yii\db\ActiveRecord;
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
class StudentDocumentList extends ActiveRecord
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
}

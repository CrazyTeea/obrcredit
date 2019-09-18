<?php

namespace app\models\app\students;

use Yii;

/**
 * This is the model class for table "student_docs".
 *
 * @property int $id
 * @property int $id_file
 * @property int $id_descriptor
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
            [['id_file', 'id_descriptor'], 'integer'],
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
        ];
    }
}

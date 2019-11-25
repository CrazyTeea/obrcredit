<?php

namespace app\models\console;

use Yii;

/**
 * This is the model class for table "student_document_types".
 *
 * @property int $id
 * @property string $descriptor
 * @property string $label
 * @property string $updated_at
 * @property string $created_at
 * @property int $system_status
 */
class StudentDocumentTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_document_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at'], 'safe'],
            [['system_status','id'], 'integer'],
            [['descriptor', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descriptor' => 'Descriptor',
            'label' => 'Label',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
        ];
    }
    public static function getActive(){
        return self::find()->where(['system_status'=>1]);
    }
}

<?php

namespace app\models\app\students;

use Yii;

/**
 * This is the model class for table "dates_education_status".
 *
 * @property int $id
 * @property int $id_student
 * @property string $date_start
 * @property string $date_end
 * @property string $updated_at
 */
class DatesEducationStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dates_education_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_student'], 'integer'],
            [['date_start', 'date_end','updated_at'], 'safe'],
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
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
        ];
    }
}

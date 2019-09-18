<?php

namespace app\models\app\students;

use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $name
 * @property int $id_org
 * @property string $code
 * @property int $education_status
 * @property string $date_education_status
 * @property string $date_create
 * @property int $status
 * @property int $osnovanie
 * @property int $grace_period
 * @property string $date_start_grace_period
 * @property string $date_end_grace_period
 */
class Students extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_org', 'education_status', 'status', 'osnovanie', 'grace_period'], 'integer'],
            [['date_education_status', 'date_create', 'date_start_grace_period', 'date_end_grace_period'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_org' => 'Id Org',
            'code' => 'Code',
            'education_status' => 'Education Status',
            'date_education_status' => 'Date Education Status',
            'date_create' => 'Date Create',
            'status' => 'Status',
            'osnovanie' => 'Osnovanie',
            'grace_period' => 'Grace Period',
            'date_start_grace_period' => 'Date Start Grace Period',
            'date_end_grace_period' => 'Date End Grace Period',
        ];
    }
}

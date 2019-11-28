<?php

namespace app\models\app\students;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "numbers_pp".
 *
 * @property int $id
 * @property string $number
 * @property int $system_status
 */
class NumbersPp extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'numbers_pp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_status'], 'integer'],
            [['number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер пп',
            'system_status' => 'System Status',
        ];
    }
    public static function getActive(){
        return self::find()->where(['system_status'=>1]);
    }
    public static function getNumbersArray(){
        return ArrayHelper::map(NumbersPp::getActive()->all(),'id','number');
    }
}

<?php

namespace app\models\app;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "banks".
 *
 * @property int $id
 * @property string $name
 * @property int $system_status
 */
class Banks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_status'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'system_status' => 'System Status',
        ];
    }
    public static function getActive(){
        return self::find()->where(['system_status'=>1]);
    }
    public static function getBanksArray(){
        return ArrayHelper::map(Banks::getActive()->all(),'id','name');
    }
}

<?php

namespace app\models\app;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "oplata".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $numberpp_id
 * @property int|null $bank_id
 * @property string|null $payment_date
 * @property string|null $latter_number
 * @property string|null $latter_date
 * @property string|null $order_number
 * @property string $order_date
 * @property string $updated_at
 * @property string $created_at
 * @property int|null $system_status
 */
class Oplata extends ActiveRecord
{
    public $payment_year;
    public $payment_month;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oplata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'numberpp_id', 'bank_id',  'system_status','status'], 'integer'],
            [['payment_date', 'order_date','latter_date', 'updated_at', 'created_at'], 'safe'],
            [['latter_number', 'order_number'], 'string', 'max' => 50],
            [['latter_number', 'order_number'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'user_id' => 'User ID',
            'numberpp_id' => 'Numberpp ID',
            'bank_id' => 'Bank ID',
            'payment_date' => 'Payment Date',
            'latter_number' => '№ письма',
            'latter_date' => 'Дата письма',
            'order_number' => '№ поручения',
            'order_date' => 'Дата поручения',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
        ];
    }
}

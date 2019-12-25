<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route_history".
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $count
 * @property string|null $route
 * @property string $updated_at
 * @property string $created_at
 */
class RouteHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user','count'], 'integer'],
            [['route'], 'string'],
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
            'id_user' => 'Id User',
            'route' => 'Route',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}

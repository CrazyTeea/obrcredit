<?php

namespace app\models\app;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property int $size
 * @property string $updated_at
 * @property string $created_at
 * @property int $system_status
 */
class Files extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'],'file'],
            [['size', 'system_status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['name', 'extension'], 'string', 'max' => 255],
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
            'extension' => 'Extension',
            'size' => 'Size',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
        ];
    }
}

<?php

namespace app\models\app\students;

use Yii;

/**
 * This is the model class for table "changes".
 *
 * @property int $id
 * @property string|null $change
 * @property string|null $comment
 * @property int|null $system_status
 */
class Changes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'changes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['change', 'comment'], 'string'],
            [['system_status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'change' => 'Причина',
            'comment' => 'Comment',
            'system_status' => 'System Status',
        ];
    }
}

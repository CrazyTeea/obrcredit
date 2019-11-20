<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m191114_114951_add_pwd_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( User::tableName(),'pwd',$this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

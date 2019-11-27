<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%students_history}}`.
 */
class m191127_091214_create_students_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%students_history}}', [
            'id' => $this->primaryKey(),
            'id_student'=>$this->integer(),
            'id_user'=>$this->integer(),
            'changes'=>$this->text(),
            'updated_at'=>$this->timestamp()->defaultExpression('NOW()')->append('ON UPDATE NOW()'),
            'created_at'=>$this->timestamp()->defaultExpression('NOW()'),
            'system_status'=>$this->integer()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%students_history}}');
    }
}

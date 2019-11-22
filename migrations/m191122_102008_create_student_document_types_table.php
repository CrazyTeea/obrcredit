<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_document_types}}`.
 */
class m191122_102008_create_student_document_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_document_types}}', [
            'id' => $this->primaryKey(),
            'descriptor'=>$this->string(),
            'label'=>$this->string(),
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
        $this->dropTable('{{%student_document_types}}');
    }
}

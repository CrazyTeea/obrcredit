<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_document_list}}`.
 */
class m191122_103036_create_student_document_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_document_list}}', [
            'id' => $this->primaryKey(),
            'id_student' => $this->integer(),
            'id_document_type' => $this->integer(),
            'id_file' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression( 'NOW()' )->append( 'ON UPDATE NOW()' ),
            'created_at' => $this->timestamp()->defaultExpression( 'NOW()' ),
            'system_status' => $this->integer()->defaultValue( 1 ),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_document_list}}');
    }
}

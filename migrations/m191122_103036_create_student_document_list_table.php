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
        $stList = (new \yii\db\Query())->from('student_docs')->all();

        $this->createTable('{{%student_document_list}}', [
            'id' => $this->primaryKey(),
            'id_student' => $this->integer(),
            'id_document_type' => $this->integer(),
            'id_file' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression( 'NOW()' )->append( 'ON UPDATE NOW()' ),
            'created_at' => $this->timestamp()->defaultExpression( 'NOW()' ),
            'system_status' => $this->integer()->defaultValue( 1 ),
        ]);


        foreach ($stList as $item){
            $doc_l = new \app\models\console\StudentDocumentList();
            $doc_l->id_student = $item['id_student'];
            $doc_l->id = $item['id'];
            $doc_l->id_document_type = $item['id_descriptor'];
            $doc_l->id_file=$item['id_file'];
            $doc_l->save(false);
        }

        $this->dropTable('student_docs');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_document_list}}');
    }
}

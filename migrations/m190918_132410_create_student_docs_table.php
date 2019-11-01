<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_docs}}`.
 */
class m190918_132410_create_student_docs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_docs}}', [
            'id' => $this->primaryKey(),
            'id_file'=>$this->tinyInteger(1),
            'id_descriptor'=>$this->tinyInteger(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_docs}}');
    }
}

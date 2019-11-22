<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%student_docs}}`.
 */
class m191122_110631_drop_student_docs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%student_docs}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%student_docs}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}

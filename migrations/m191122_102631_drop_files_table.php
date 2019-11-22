<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%files}}`.
 */
class m191122_102631_drop_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%files}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}

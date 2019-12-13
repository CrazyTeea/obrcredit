<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%changes}}`.
 */
class m191206_073927_create_changes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%changes}}', [
            'id' => $this->primaryKey(),
            'change'=>$this->text(),
            'comment'=>$this->text(),
            'system_status'=>$this->integer()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%changes}}');
    }
}

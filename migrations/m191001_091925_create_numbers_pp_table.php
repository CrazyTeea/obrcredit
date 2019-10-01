<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%numbers_pp}}`.
 */
class m191001_091925_create_numbers_pp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%numbers_pp}}', [
            'id' => $this->primaryKey(),
            'number'=>$this->string(),
            'system_status'=>$this->integer()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%numbers_pp}}');
    }
}

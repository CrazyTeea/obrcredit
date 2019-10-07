<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banks}}`.
 */
class m191001_091852_create_banks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%banks}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'system_status'=>$this->integer()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%banks}}');
    }
}

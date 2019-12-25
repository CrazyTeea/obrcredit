<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%route_history}}`.
 */
class m191224_065749_create_route_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%route_history}}', [
            'id' => $this->primaryKey(),
            'id_user'=>$this->integer(),
            'route'=>$this->text(),
            'updated_at'=>$this->timestamp()->defaultExpression('NOW()')->append('ON UPDATE NOW()'),
            'created_at'=>$this->timestamp()->defaultExpression('NOW()'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%route_history}}');
    }
}

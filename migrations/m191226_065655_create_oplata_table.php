<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%oplata}}`.
 */
class m191226_065655_create_oplata_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%oplata}}', [
            'id' => $this->primaryKey(),
            'org_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'numberpp_id'=>$this->integer(),
            'bank_id'=>$this->integer(),
            'payment_date'=>$this->date(),
            'latter_number'=>$this->string(50),
            'latter_date'=>$this->date(),
            'order_number'=>$this->string(50),
            'order_date'=>$this->date(),
            'updated_at'=>$this->timestamp()->defaultExpression('NOW()')->append('ON UPDATE NOW()'),
            'created_at'=>$this->timestamp()->defaultExpression('NOW()'),
            'system_status'=>$this->integer()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%oplata}}');
    }
}

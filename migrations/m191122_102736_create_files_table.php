<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m191122_102736_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'extension' => $this->string(),
            'size'=>$this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression( 'NOW()' )->append( 'ON UPDATE NOW()' ),
            'created_at' => $this->timestamp()->defaultExpression( 'NOW()' ),
            'system_status'=>$this->integer()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}

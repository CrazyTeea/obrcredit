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

        $oldFiles = (new \yii\db\Query())->from('files')->all();

        $this->dropTable('files');

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'extension' => $this->string(),
            'size'=>$this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression( 'NOW()' )->append( 'ON UPDATE NOW()' ),
            'created_at' => $this->timestamp()->defaultExpression( 'NOW()' ),
            'system_status'=>$this->integer()->defaultValue(1),
        ]);

        foreach ($oldFiles as $oldFile){
            $file = new \app\models\console\Files();
            $file->id = $oldFile['id'];
            $file->name = $oldFile['name'];
            $file->extension=$oldFile['extension'];
            $file->save(false);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}

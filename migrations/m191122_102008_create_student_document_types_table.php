<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_document_types}}`.
 */
class m191122_102008_create_student_document_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_document_types}}', [
            'id' => $this->primaryKey(),
            'descriptor'=>$this->string(),
            'label'=>$this->string(2550),
            'updated_at'=>$this->timestamp()->defaultExpression('NOW()')->append('ON UPDATE NOW()'),
            'created_at'=>$this->timestamp()->defaultExpression('NOW()'),
            'system_status'=>$this->integer()->defaultValue(1),
        ]);


        $oldDescriptions = (new \yii\db\Query())->from('descriptions')->select('*')->all();



        foreach ($oldDescriptions as $oldDescription) {
            $st = new \app\models\console\StudentDocumentTypes();
            $st->id = $oldDescription['id'];
            $st->descriptor = $oldDescription['name'];
            $st->save(false);
        }
        $this->dropTable('descriptions');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_document_types}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%students}}`.
 */
class m190918_131235_create_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%students}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'id_org'=>$this->tinyInteger(1),
            'code'=>$this->string(),
            'education_status'=>$this->tinyInteger(1),
            'date_education_status'=>$this->date(),
            'date_create'=>$this->date(),
            'status'=>$this->tinyInteger(1),
            'osnovanie'=>$this->tinyInteger(1),
            'grace_period'=>$this->tinyInteger(1),
            'date_start_grace_period'=>$this->date(),
            'date_end_grace_period'=>$this->date(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%students}}');
    }
}

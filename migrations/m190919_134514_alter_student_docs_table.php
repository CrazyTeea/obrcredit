<?php

use yii\db\Migration;

/**
 * Class m190919_134514_alter_student_docs_table
 */
class m190919_134514_alter_student_docs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student_docs','id_student',$this->tinyInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190919_134514_alter_student_docs_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190919_134514_alter_student_docs_table cannot be reverted.\n";

        return false;
    }
    */
}

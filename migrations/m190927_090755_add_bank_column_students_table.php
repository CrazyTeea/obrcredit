<?php

use yii\db\Migration;

/**
 * Class m190927_090755_add_bank_column_students_table
 */
class m190927_090755_add_bank_column_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','bank',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190927_090755_add_bank_column_students_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190927_090755_add_bank_column_students_table cannot be reverted.\n";

        return false;
    }
    */
}

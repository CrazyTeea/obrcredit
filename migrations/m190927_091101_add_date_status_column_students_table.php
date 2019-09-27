<?php

use yii\db\Migration;

/**
 * Class m190927_091101_add_date_status_column_students_table
 */
class m190927_091101_add_date_status_column_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','date_status',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190927_091101_add_date_status_column_students_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190927_091101_add_date_status_column_students_table cannot be reverted.\n";

        return false;
    }
    */
}

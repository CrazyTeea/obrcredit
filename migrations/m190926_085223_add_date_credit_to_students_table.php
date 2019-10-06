<?php

use yii\db\Migration;

/**
 * Class m190926_085223_add_date_credit_to_students_table
 */
class m190926_085223_add_date_credit_to_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','date_credit',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190926_085223_add_date_credit_to_students_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190926_085223_add_date_credit_to_students_table cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m200827_062158_add_abiturient_students
 */
class m200827_062158_add_abiturient_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','ext_status',$this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200827_062158_add_abiturient_students cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200827_062158_add_abiturient_students cannot be reverted.\n";

        return false;
    }
    */
}

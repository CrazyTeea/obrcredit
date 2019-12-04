<?php

use yii\db\Migration;

/**
 * Class m191128_094811_add_id_user_to_and_rename_id_user
 */
class m191128_094811_add_id_user_to_and_rename_id_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('students_history','id_user','id_user_from');
        $this->addColumn('students_history','id_user_to',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191128_094811_add_id_user_to_and_rename_id_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191128_094811_add_id_user_to_and_rename_id_user cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m191004_082336_add_date_start_grace2_period
 */
class m191004_082336_add_date_start_grace2_period extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','date_start_grace_period2',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191004_082336_add_date_start_grace2_period cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191004_082336_add_date_start_grace2_period cannot be reverted.\n";

        return false;
    }
    */
}

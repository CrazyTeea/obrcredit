<?php

use yii\db\Migration;

/**
 * Class m191004_082324_add_date_end_grace1_period
 */
class m191004_082324_add_date_end_grace1_period extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','date_end_grace_period1',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191004_082324_add_date_end_grace1_period cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191004_082324_add_date_end_grace1_period cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m191004_082409_change_date_grace_period
 */
class m191004_082409_change_date_grace_period extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('students','date_start_grace_period','date_start_grace_period3');
        $this->renameColumn('students','date_end_grace_period','date_end_grace_period3');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191004_082409_change_date_grace_period cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191004_082409_change_date_grace_period cannot be reverted.\n";

        return false;
    }
    */
}

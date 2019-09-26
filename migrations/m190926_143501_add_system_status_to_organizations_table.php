<?php

use yii\db\Migration;

/**
 * Class m190926_143501_add_system_status_to_organizations_table
 */
class m190926_143501_add_system_status_to_organizations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('organizations','system_status',$this->tinyInteger());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190926_143501_add_system_status_to_organizations_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190926_143501_add_system_status_to_organizations_table cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m190920_081927_add_id_org_column_at_user_table
 */
class m190920_081927_add_id_org_column_at_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('user','id_org',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190920_081927_add_id_org_column_at_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190920_081927_add_id_org_column_at_user_table cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m190920_081004_add_name_column_at_user_table
 */
class m190920_081004_add_name_column_at_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','name',$this->string(500));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190920_081004_add_name_column_at_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190920_081004_add_name_column_at_user_table cannot be reverted.\n";

        return false;
    }
    */
}

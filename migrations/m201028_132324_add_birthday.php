<?php

use yii\db\Migration;

/**
 * Class m201028_132324_add_birthday
 */
class m201028_132324_add_birthday extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('students','birthday',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201028_132324_add_birthday cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201028_132324_add_birthday cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m191213_133705_add_old_code_students
 */
class m191213_133705_add_old_code_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\app\students\Students::tableName(),'old_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191213_133705_add_old_code_students cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191213_133705_add_old_code_students cannot be reverted.\n";

        return false;
    }
    */
}

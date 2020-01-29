<?php

use yii\db\Migration;

/**
 * Class m200128_080806_add_act_date
 */
class m200128_080806_add_act_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\app\students\Students::tableName(),'date_act',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200128_080806_add_act_date cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200128_080806_add_act_date cannot be reverted.\n";

        return false;
    }
    */
}

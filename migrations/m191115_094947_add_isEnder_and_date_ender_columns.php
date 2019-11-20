<?php

use app\models\app\students\Students;
use yii\db\Migration;

/**
 * Class m191115_094947_add_isEnder_and_date_ender_columns
 */
class m191115_094947_add_isEnder_and_date_ender_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( Students::tableName(),'isEnder',$this->boolean()->defaultValue(0));
        $this->addColumn( Students::tableName(),'date_ender',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191115_094947_add_isEnder_and_date_ender_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191115_094947_add_isEnder_and_date_ender_columns cannot be reverted.\n";

        return false;
    }
    */
}

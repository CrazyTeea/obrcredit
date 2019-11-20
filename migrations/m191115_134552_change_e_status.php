<?php

use app\models\app\students\DatesEducationStatus;
use yii\db\Migration;

/**
 * Class m191115_134552_change_e_status
 */
class m191115_134552_change_e_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn( DatesEducationStatus::tableName(),'date_start',$this->date()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191115_134552_change_e_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191115_134552_change_e_status cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use app\models\app\students\Students;
use yii\db\Migration;

/**
 * Class m191119_131014_change_status_students
 */
class m191119_131014_change_status_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn( Students::tableName(),'status',$this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191119_131014_change_status_students cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191119_131014_change_status_students cannot be reverted.\n";

        return false;
    }
    */
}

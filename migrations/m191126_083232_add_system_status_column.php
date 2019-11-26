<?php

use yii\db\Migration;

/**
 * Class m191126_083232_add_system_status_column
 */
class m191126_083232_add_system_status_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\app\students\Students::tableName(),'system_status',$this->integer()->defaultValue(1));


        $students = \app\models\app\students\Students::find()->all();
        foreach ($students as $student)
        {
            $student->system_status=1;
            $student->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191126_083232_add_system_status_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191126_083232_add_system_status_column cannot be reverted.\n";

        return false;
    }
    */
}

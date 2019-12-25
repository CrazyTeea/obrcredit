<?php

use yii\db\Migration;

/**
 * Class m191206_073857_change_changes_column
 */
class m191206_073857_change_changes_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(\app\models\app\students\StudentsHistory::tableName(),'changes');
        $this->addColumn(\app\models\app\students\StudentsHistory::tableName(),'id_change',$this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191206_073857_change_changes_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191206_073857_change_changes_column cannot be reverted.\n";

        return false;
    }
    */
}

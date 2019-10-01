<?php

use yii\db\Migration;

/**
 * Class m191001_092144_rename_number_bank_column
 */
class m191001_092144_rename_number_bank_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('students','bank',$this->integer());
        $this->renameColumn('students','bank','id_bank');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191001_092144_rename_number_bank_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191001_092144_rename_number_bank_column cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m191001_092127_rename_number_credit_pp_column
 */
class m191001_092127_rename_number_credit_pp_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('students','number_pp_credit',$this->integer());
        $this->renameColumn('students','number_pp_credit','id_number_pp');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191001_092127_rename_number_credit_pp_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191001_092127_rename_number_credit_pp_column cannot be reverted.\n";

        return false;
    }
    */
}

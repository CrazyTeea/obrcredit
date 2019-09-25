<?php

use yii\db\Migration;

/**
 * Class m190925_090242_rename_date_education_status_id_date_education_status_at_students_table
 */
class m190925_090242_rename_date_education_status_id_date_education_status_at_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('students','date_education_status');
        $this->addColumn('students','id_date_e_status',$this->integer(11));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190925_090242_rename_date_education_status_id_date_education_status_at_students_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190925_090242_rename_date_education_status_id_date_education_status_at_students_table cannot be reverted.\n";

        return false;
    }
    */
}

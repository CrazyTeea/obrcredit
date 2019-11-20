<?php

use app\models\app\students\Students;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%students}}`.
 */
class m191024_111303_add_date_start_column_to_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( Students::tableName(),'date_start',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

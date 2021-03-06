<?php

use app\models\app\students\Students;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%students}}`.
 */
class m191025_120019_add_perevod_column_to_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( Students::tableName(),'perevod',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

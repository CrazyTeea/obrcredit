<?php

use app\models\app\Oplata;
use yii\db\Migration;

/**
 * Class m191226_085022_change_oplata_table
 */
class m191226_085022_change_oplata_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(Oplata::tableName(),'user_id');
        $this->dropColumn(Oplata::tableName(),'org_id');
        $this->addColumn(Oplata::tableName(),'status',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191226_085022_change_oplata_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191226_085022_change_oplata_table cannot be reverted.\n";

        return false;
    }
    */
}

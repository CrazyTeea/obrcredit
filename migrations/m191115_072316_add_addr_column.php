<?php

use app\models\app\Organizations;
use yii\db\Migration;

/**
 * Class m191115_072316_add_addr_column
 */
class m191115_072316_add_addr_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( Organizations::tableName(),'addr',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191115_072316_add_addr_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191115_072316_add_addr_column cannot be reverted.\n";

        return false;
    }
    */
}

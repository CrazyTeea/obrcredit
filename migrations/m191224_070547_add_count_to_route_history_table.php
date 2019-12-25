<?php

use yii\db\Migration;

/**
 * Class m191224_070547_add_count_to_route_history_table
 */
class m191224_070547_add_count_to_route_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\RouteHistory::tableName(),'count',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191224_070547_add_count_to_route_history_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191224_070547_add_count_to_route_history_table cannot be reverted.\n";

        return false;
    }
    */
}

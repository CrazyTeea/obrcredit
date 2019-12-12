<?php

use yii\db\Migration;

/**
 * Class m191212_100917_set_default
 */
class m191212_100917_set_default extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(\app\models\app\students\Students::tableName(),'osnovanie',$this->integer()->defaultValue(0));
        $this->alterColumn(\app\models\app\students\Students::tableName(),'grace_period',$this->integer()->defaultValue(0));
        $sts = \app\models\app\students\Students::find()->all();
        foreach ($sts as $st)
        {
            if (!$st->osnovanie)
                $st->osnovanie = 0;
            if (!$st->grace_period)
                $st->grace_period = 0;
            $st->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191212_100917_set_default cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191212_100917_set_default cannot be reverted.\n";

        return false;
    }
    */
}

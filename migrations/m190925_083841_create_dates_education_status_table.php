<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dates_education_status}}`.
 */
class m190925_083841_create_dates_education_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dates_education_status}}', [
            'id' => $this->primaryKey(),
            'id_student' => $this->Integer(11),
            'date_start' => $this->date()->notNull(),
            'date_end' => $this->date(),
            'updated_at' => $this->timestamp()->notNull()
        ]);
        $sql =<<<SQL
            CREATE TRIGGER `date_start_INSERT` BEFORE INSERT ON
            `dates_education_status` 
            FOR EACH ROW BEGIN
            SET new.date_start=curdate();
            SET new.updated_at = now();
            END;
            
            CREATE TRIGGER `updated_at_UPDATE` BEFORE UPDATE ON
            `dates_education_status` 
            FOR EACH ROW BEGIN
            SET new.updated_at = now();
            END;
            
            SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dates_education_status}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%audit}}`.
 */
class m190817_184504_create_audit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%audit}}', [
            'id' => $this->primaryKey(),
            'table_name' => $this->string(),
            'foreign_key' => $this->integer(),
            'data_1_old_val' => $this->string(),
            'data_1_new_val' => $this->string(),
            'data_2_old_val' => $this->string(),
            'data_2_new_val' => $this->string(),
            'data_3_old_val' => $this->string(),
            'data_3_new_val' => $this->string(),
            'user_notes' => $this->text(),
            'modified_by' => $this->string()->notNull(),
            'modified_date_time' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%audit}}');
    }
}

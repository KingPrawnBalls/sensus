<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visitor}}`.
 */
class m190804_071609_create_visitor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%visitor}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'check_in_dt' => $this->dateTime()->notNull(),
            'checked_in_by' => $this->integer()->notNull(),
            'visiting' => $this->string(),
            'notes' => $this->text(),
            'check_out_dt' => $this->dateTime(),
            'checked_out_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%visitor}}');
    }
}

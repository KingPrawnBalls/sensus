<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dates}}`.
 */
class m190730_075348_create_dates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dates}}', [
            'id' => $this->primaryKey(),
            'year' => $this->integer(4)->notNull(),
            'date' => $this->date()->notNull(),
            'type' => $this->string()->notNull(),
            'additional_data' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dates}}');
    }
}

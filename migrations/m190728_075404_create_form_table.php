<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%form}}`.
 */
class m190728_075404_create_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form}}', [
            'id' => $this->primaryKey(),
            'year' => $this->integer(4)->notNull(),
            'name' => $this->string()->notNull(),
            'status' => $this->char(1)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%form}}');
    }
}

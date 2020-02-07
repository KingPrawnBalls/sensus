<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attendance_code}}`.
 */
class m200205_085849_create_attendance_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attendance_code}}', [
            'id' => $this->primaryKey(),
            'code' => $this->char(3)->notNull(),
            'description' => $this->string()->notNull(),
            'long_description' => $this->string(),
            'statistical_meaning' => $this->char(3)->notNull(),
            'is_on_premises' => $this->boolean()->notNull(),
            'status' => $this->char(1)->notNull()->defaultValue('A')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%attendance_code}}');
    }
}

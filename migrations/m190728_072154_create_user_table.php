<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190728_072154_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('Users');

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull(),
            'full_name' => $this->string()->notNull(),
            'email' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(),
            'user_type' => $this->string()->notNull(),
            'status' => $this->char(1)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

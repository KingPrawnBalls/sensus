<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m190727_204154_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull(),
            'full_name' => $this->string()->notNull(),
            'email' => $this->string(),
            'status' => $this->char(1)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string()
        ]);

        $this->insert('{{%users}}', [
            'user_name' => 'pharding',
            'full_name' => 'Dummy User',
            'status' => 'A',
            'password_hash' => password_hash('{{INSERT DEFAULT HERE}}', PASSWORD_DEFAULT)
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}

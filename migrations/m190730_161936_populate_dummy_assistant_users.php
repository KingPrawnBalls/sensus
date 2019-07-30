<?php

use yii\db\Migration;

/**
 * Class m190730_161936_populate_dummy_assistant_users
 */
class m190730_161936_populate_dummy_assistant_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'user_name' => 'assistant1',
            'full_name' => 'Assistant 1',
            'email' => 'assistant@chips.com',
            'password_hash' => password_hash('{{INSERT_PWD_HERE}}', PASSWORD_DEFAULT),
            'user_type' => 'ASSISTANT',
            'status' => 'A',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190730_161936_populate_dummy_assistant_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_161936_populate_dummy_assistant_users cannot be reverted.\n";

        return false;
    }
    */
}

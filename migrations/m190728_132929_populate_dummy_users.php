<?php

use yii\db\Migration;

/**
 * Class m190728_132929_populate_dummy_users
 */
class m190728_132929_populate_dummy_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $defaultPassword = '{{INSERT_PWD_HERE}}';
        
        $this->insert('{{%user}}', [
            'user_name' => 'pharding',
            'full_name' => 'Paul H',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'TEACHER',
            'status' => 'A',
        ]);

        $this->insert('{{%user}}', [
            'user_name' => 'teacher2',
            'full_name' => 'Teacher 2',
            'email' => 'fish@chips.com',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'TEACHER',
            'status' => 'A',
        ]);

        $this->insert('{{%user}}', [
            'user_name' => 'admin1',
            'full_name' => 'Admin 1',
            'email' => 'admin@chips.com',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'ADMIN',
            'status' => 'A',
        ]);

        $this->insert('{{%user}}', [
            'user_name' => 'superuser1',
            'full_name' => 'Super 1',
            'email' => 'super@chips.com',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'SUPERUSER',
            'status' => 'A',
        ]);

        $this->insert('{{%user}}', [
            'user_name' => 'inactive1',
            'full_name' => 'Inactive 1',
            'email' => 'inactive@chips.com',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'SUPERUSER',
            'status' => 'I',
        ]);

        $this->insert('{{%user}}', [
            'user_name' => 'deleted1',
            'full_name' => 'Deleted 1',
            'email' => 'deleted@chips.com',
            'password_hash' => password_hash($defaultPassword, PASSWORD_DEFAULT),
            'user_type' => 'ADMIN',
            'status' => 'D',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%user}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190728_132929_populate_dummy_data cannot be reverted.\n";

        return false;
    }
    */
}

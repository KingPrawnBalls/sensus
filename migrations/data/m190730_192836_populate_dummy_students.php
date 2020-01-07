<?php

use yii\db\Migration;

/**
 * Class m190730_192836_populate_dummy_students
 */
class m190730_192836_populate_dummy_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%student}}', [

            'first_name' => 'Pika',
            'last_name' => 'Podafuwa',
            'status' => 'A',
        ]);

        $this->insert('{{%student}}', [

            'first_name' => 'Blessica',
            'last_name' => 'Blosta',
            'status' => 'A',
        ]);

        $this->insert('{{%student}}', [

            'first_name' => 'Seth',
            'last_name' => 'Sarding',
            'status' => 'A',
        ]);

        $this->insert('{{%student}}', [

            'first_name' => 'In-',
            'last_name' => 'Active',
            'status' => 'I',
        ]);

        $this->insert('{{%student}}', [

            'first_name' => 'De-',
            'last_name' => 'leted',
            'status' => 'D',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%student}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_192836_populate_dummy_students cannot be reverted.\n";

        return false;
    }
    */
}

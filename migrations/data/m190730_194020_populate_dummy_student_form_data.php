<?php

use yii\db\Migration;

/**
 * Class m190730_194020_populate_dummy_student_form_data
 */
class m190730_194020_populate_dummy_student_form_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%form}}', [

            'year' => '2018',
            'name' => 'MJLC',
            'status' => 'A',
        ]);

        $this->insert('{{%form}}', [

            'year' => '2018',
            'name' => 'UJLC',
            'status' => 'A',
        ]);

        $this->insert('{{%form}}', [

            'year' => '2018',
            'name' => 'LJLC',
            'status' => 'A',
        ]);

        // --------------

        $this->insert('{{%student_form}}', [
            'student_id' => 1,
            'form_id' => 1,
        ]);

        $this->insert('{{%student_form}}', [
            'student_id' => 2,
            'form_id' => 1,
        ]);

        $this->insert('{{%student_form}}', [
            'student_id' => 3,
            'form_id' => 1,
        ]);

        $this->insert('{{%student_form}}', [
            'student_id' => 4,
            'form_id' => 1,
        ]);

        $this->insert('{{%student_form}}', [
            'student_id' => 5,
            'form_id' => 1,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%student_form}}');
        $this->truncateTable('{{%form}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_194020_populate_dummy_student_form_data cannot be reverted.\n";

        return false;
    }
    */
}

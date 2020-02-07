<?php

use yii\db\Migration;

/**
 * Class m200207_143542_alter_table_student_add_column_notes
 */
class m200207_143542_alter_table_student_add_column_notes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student', 'notes',  \yii\db\Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('student', 'notes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_143542_alter_table_student_add_column_notes cannot be reverted.\n";

        return false;
    }
    */
}

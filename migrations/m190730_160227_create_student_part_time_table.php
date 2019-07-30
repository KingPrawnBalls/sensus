<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_part_time}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%student}}`
 */
class m190730_160227_create_student_part_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_part_time}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'effective_date' => $this->date()->notNull(),
            'monday_am' => $this->boolean()->notNull(),
            'monday_pm' => $this->boolean()->notNull(),
            'tuesday_am' => $this->boolean()->notNull(),
            'tuesday_pm' => $this->boolean()->notNull(),
            'wednesday_am' => $this->boolean()->notNull(),
            'wednesday_pm' => $this->boolean()->notNull(),
            'thursday_am' => $this->boolean()->notNull(),
            'thursday_pm' => $this->boolean()->notNull(),
            'friday_am' => $this->boolean()->notNull(),
            'friday_pm' => $this->boolean()->notNull(),
        ]);

        // creates index for column `student_id`
        $this->createIndex(
            '{{%idx-student_part_time-student_id}}',
            '{{%student_part_time}}',
            'student_id'
        );

        // add foreign key for table `{{%student}}`
        $this->addForeignKey(
            '{{%fk-student_part_time-student_id}}',
            '{{%student_part_time}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%student}}`
        $this->dropForeignKey(
            '{{%fk-student_part_time-student_id}}',
            '{{%student_part_time}}'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            '{{%idx-student_part_time-student_id}}',
            '{{%student_part_time}}'
        );

        $this->dropTable('{{%student_part_time}}');
    }
}

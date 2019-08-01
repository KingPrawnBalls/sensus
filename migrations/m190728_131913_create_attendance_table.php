<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attendance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%form}}`
 * - `{{%student}}`
 * - `{{%user}}`
 */
class m190728_131913_create_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attendance}}', [
            'id' => $this->primaryKey(),
            'form_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'period' => $this->integer(1)->notNull(),
            'attendance_code' => $this->char(1)->notNull(),
            'last_modified' => $this->dateTime()->notNull(),
            'last_modified_by' => $this->integer()->notNull(),
        ]);

        // creates index for column `form_id`
        $this->createIndex(
            '{{%idx-attendance-form_id}}',
            '{{%attendance}}',
            'form_id'
        );

        // add foreign key for table `{{%form}}`
        $this->addForeignKey(
            '{{%fk-attendance-form_id}}',
            '{{%attendance}}',
            'form_id',
            '{{%form}}',
            'id',
            'CASCADE'
        );

        // creates index for column `student_id`
        $this->createIndex(
            '{{%idx-attendance-student_id}}',
            '{{%attendance}}',
            'student_id'
        );

        // add foreign key for table `{{%student}}`
        $this->addForeignKey(
            '{{%fk-attendance-student_id}}',
            '{{%attendance}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-attendance-user_id}}',
            '{{%attendance}}',
            'last_modified_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-attendance-user_id}}',
            '{{%attendance}}'
        );

        // drops foreign key for table `{{%form}}`
        $this->dropForeignKey(
            '{{%fk-attendance-form_id}}',
            '{{%attendance}}'
        );

        // drops index for column `form_id`
        $this->dropIndex(
            '{{%idx-attendance-form_id}}',
            '{{%attendance}}'
        );

        // drops foreign key for table `{{%student}}`
        $this->dropForeignKey(
            '{{%fk-attendance-student_id}}',
            '{{%attendance}}'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            '{{%idx-attendance-student_id}}',
            '{{%attendance}}'
        );

        $this->dropTable('{{%attendance}}');
    }
}

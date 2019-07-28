<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_form}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%student}}`
 * - `{{%form}}`
 */
class m190728_123543_create_junction_table_for_student_and_form_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%student_form}}', [
            'student_id' => $this->integer(),
            'form_id' => $this->integer(),
            'PRIMARY KEY(student_id, form_id)',
        ]);

        // creates index for column `student_id`
        $this->createIndex(
            '{{%idx-student_form-student_id}}',
            '{{%student_form}}',
            'student_id'
        );

        // add foreign key for table `{{%student}}`
        $this->addForeignKey(
            '{{%fk-student_form-student_id}}',
            '{{%student_form}}',
            'student_id',
            '{{%student}}',
            'id',
            'CASCADE'
        );

        // creates index for column `form_id`
        $this->createIndex(
            '{{%idx-student_form-form_id}}',
            '{{%student_form}}',
            'form_id'
        );

        // add foreign key for table `{{%form}}`
        $this->addForeignKey(
            '{{%fk-student_form-form_id}}',
            '{{%student_form}}',
            'form_id',
            '{{%form}}',
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
            '{{%fk-student_form-student_id}}',
            '{{%student_form}}'
        );

        // drops index for column `student_id`
        $this->dropIndex(
            '{{%idx-student_form-student_id}}',
            '{{%student_form}}'
        );

        // drops foreign key for table `{{%form}}`
        $this->dropForeignKey(
            '{{%fk-student_form-form_id}}',
            '{{%student_form}}'
        );

        // drops index for column `form_id`
        $this->dropIndex(
            '{{%idx-student_form-form_id}}',
            '{{%student_form}}'
        );

        $this->dropTable('{{%student_form}}');
    }
}

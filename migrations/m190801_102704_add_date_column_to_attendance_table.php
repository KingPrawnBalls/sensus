<?php

use yii\db\Migration;

/**
 * Handles adding date to table `{{%attendance}}`.
 */
class m190801_102704_add_date_column_to_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attendance}}', 'date', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}

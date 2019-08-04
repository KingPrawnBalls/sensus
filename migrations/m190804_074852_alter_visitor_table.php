<?php

use yii\db\Migration;

/**
 * Class m190804_074852_alter_visitor_table
 */
class m190804_074852_alter_visitor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // add 2 foreign keys for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-visitor-user_id}}',
            '{{%visitor}}',
            'checked_in_by',
            '{{%user}}',
            'id',
            'NO ACTION'
        );

        $this->addForeignKey(
            '{{%fk-visitor-user_id_2}}',
            '{{%visitor}}',
            'checked_out_by',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign keys for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-visitor-user_id}}',
            '{{%visitor}}'
        );

        $this->dropForeignKey(
            '{{%fk-visitor-user_id_2}}',
            '{{%visitor}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190804_074852_alter_visitor_table cannot be reverted.\n";

        return false;
    }
    */
}

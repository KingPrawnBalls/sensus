<?php

use yii\db\Migration;

/**
 * Class m190822_134631_create_index_on_audite
 */
class m190822_134631_create_index_on_audite extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            '{{%idx-audit-table-and-key}}',
            '{{%audit}}',
            [
                'table_name',
                'foreign_key'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-audit-table-and-key}}',
            '{{%audit}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190822_134631_create_index_on_audite cannot be reverted.\n";

        return false;
    }
    */
}

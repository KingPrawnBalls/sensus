<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit".
 *
 * @property int $id
 * @property string $table_name
 * @property int $foreign_key
 * @property string $data_1_old_val
 * @property string $data_1_new_val
 * @property string $data_2_old_val
 * @property string $data_2_new_val
 * @property string $data_3_old_val
 * @property string $data_3_new_val
 * @property string $user_notes
 * @property string $modified_by
 * @property string $modified_date_time
 */
class Audit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['foreign_key'], 'integer'],
            [['user_notes'], 'string'],
            [['modified_by', 'modified_date_time'], 'required'],
            [['modified_date_time'], 'safe'],
            [['table_name', 'data_1_old_val', 'data_1_new_val', 'data_2_old_val', 'data_2_new_val', 'data_3_old_val', 'data_3_new_val', 'modified_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_name' => 'Table Name',
            'foreign_key' => 'Foreign Key',
            'data_1_old_val' => 'Data 1 Old Val',
            'data_1_new_val' => 'Data 1 New Val',
            'data_2_old_val' => 'Data 2 Old Val',
            'data_2_new_val' => 'Data 2 New Val',
            'data_3_old_val' => 'Data 3 Old Val',
            'data_3_new_val' => 'Data 3 New Val',
            'user_notes' => 'User Notes',
            'modified_by' => 'Modified By',
            'modified_date_time' => 'Modified Date Time',
        ];
    }
}

<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "visitor".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $check_in_dt
 * @property int $checked_in_by
 * @property string $visiting
 * @property string $notes
 * @property string $check_out_dt
 * @property int $checked_out_by
 */
class Visitor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'check_in_dt', 'checked_in_by'], 'required'],
            [['check_in_dt', 'check_out_dt'], 'safe'],
            [['checked_in_by', 'checked_out_by'], 'integer'],
            [['notes'], 'string'],
            [['first_name', 'last_name', 'visiting'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'check_in_dt' => 'Check In Time',
            'checked_in_by' => 'Checked In By',
            'visiting' => 'Visiting Who?',
            'notes' => 'Notes',
            'check_out_dt' => 'Check Out Time',
            'checked_out_by' => 'Checked Out By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedInBy()
    {
        return $this->hasOne(User::className(), ['id' => 'checked_in_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedOutBy()
    {
        return $this->hasOne(User::className(), ['id' => 'checked_out_by']);
    }

    /**
     * @return ActiveDataProvider
     */
    public static function findAllCheckedInNow()
    {
        $midnight = date(Yii::$app->params['dbDateTimeFormat'], strtotime('today midnight'));
        $now = date(Yii::$app->params['dbDateTimeFormat']);

        return new ActiveDataProvider([
            'query' => self::find()
                ->andWhere('check_out_dt is null')
                ->andWhere("check_in_dt between '$midnight' and '$now'")
                ->addOrderBy('check_in_dt'),
        ]);
    }

    /**
     * {@inheritdoc}
     * @return VisitorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VisitorQuery(get_called_class());
    }
}

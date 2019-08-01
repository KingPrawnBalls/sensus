<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property int $id
 * @property int $form_id
 * @property int $student_id
 * @property date $date
 * @property int $period
 * @property string $attendance_code
 * @property datetime $last_modified
 * @property int $last_modified_by
 *
 */
class Attendance extends \yii\db\ActiveRecord
{
    const ATTENDANCE_PERIOD_MORNING = 1;
    const ATTENDANCE_PERIOD_AFTERNOON = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attendance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['form_id', 'student_id', 'period', 'attendance_code', 'last_modified', 'last_modified_by'], 'required'],
            [['form_id', 'student_id', 'period', 'last_modified_by'], 'integer'],
          //  [['last_modified'], 'safe'],
            [['attendance_code'], 'string', 'max' => 1],
            ['attendance_code', 'in', 'range' => ['0', '1', 'L']],  //TODO - all attendance codes
            ['period', 'in', 'range' => [self::ATTENDANCE_PERIOD_MORNING, self::ATTENDANCE_PERIOD_AFTERNOON]],
            [['last_modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['last_modified_by' => 'id']],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => Form::className(), 'targetAttribute' => ['form_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_id' => 'Form ID',
            'student_id' => 'Student ID',
            'period' => 'Period',
            'attendance_code' => 'Attendance Code',
            'last_modified' => 'Last Modified',
            'last_modified_by' => 'Last Modified By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'last_modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'student_id']);
    }

    /**
     * {@inheritdoc}
     * @return AttendanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttendanceQuery(get_called_class());
    }
}

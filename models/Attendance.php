<?php

namespace app\models;

use http\Exception\RuntimeException;
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

    //Per https://assets.publishing.service.gov.uk/government/uploads/system/uploads/attachment_data/file/818204/School_attendance_July_2019.pdf
    const ATTENDANCE_VALID_CODES = [
        '0' => '[Not yet recorded]',
        '1' => 'Present in school',
        'L' => 'Late arrival before the register has closed',
        'B' => 'Off-site educational activity',
        'D' => 'Dual Registered - at another educational establishment',
        'J' => 'At an interview with prospective employers, or another educational establishment',
        'P' => 'Participating in a supervised sporting activity',
        'V' => 'Educational visit or trip',
        'W' => 'Work experience',
        'C' => 'Leave of absence authorised by the school',
        'E' => 'Excluded but no alternative provision made',
        'H' => 'Holiday authorised by the school',
        'I' => 'Illness (not medical or dental appointments)',
        'M' => 'Medical or dental appointments',
        'R' => 'Religious observance',
        'S' => 'Study leave',
        'T' => 'Gypsy, Roma and Traveller absence',
        'G' => 'Holiday not authorised by the school',
        'N' => 'Reason for absence not yet provided',
        'O' => 'Absent from school without authorisation',
        'U' => 'Arrived in school after registration closed',
        'X' => 'Not required to be in school',
        'Y' => 'Unable to attend due to exceptional circumstances',
        'Z' => 'Pupil not on admission register',
        '#' => 'Planned whole or partial school closure'
    ];

    public static function getAttendanceCodeForDisplay($code) {
        return self::ATTENDANCE_VALID_CODES[$code];
    }

    public static function getCurrentPeriod() {
        $hour = date('H');
        return ($hour > 12) ? Attendance::ATTENDANCE_PERIOD_AFTERNOON : Attendance::ATTENDANCE_PERIOD_MORNING;
    }

    public static function formatPeriodForDisplay($period) {
        switch ($period) {
            case Attendance::ATTENDANCE_PERIOD_MORNING:
                return 'morning';
                break;
            case Attendance::ATTENDANCE_PERIOD_AFTERNOON:
                return 'afternoon';
                break;
            default:
                throw new RuntimeException("Unknown Attendance Period uncountered: $period");
        }
    }

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
            ['attendance_code', 'in', 'range' => array_keys(self::ATTENDANCE_VALID_CODES)],
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
            'attendance_code' => '',
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

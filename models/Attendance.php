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
 * @property string $attendance_code_1
 * @property string $attendance_code_2
 * @property datetime $last_modified
 * @property int $last_modified_by
 *
 */
class Attendance extends \yii\db\ActiveRecord
{
    //NOTE: Adjust this array to support more than 2 daily registration periods
    const ATTENDANCE_PERIOD_LABELS = [
        1 => 'am',
        2 => 'pm',
    ];

    //NOTE: Adjust this array to support more than 2 daily registration periods
    const ATTENDANCE_PERIOD_LABELS_LONG = [
        1 => 'morning',
        2 => 'afternoon',
    ];

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

    const ATTENDANCE_CODES_ON_PREMISES = ['0', '1', 'L', 'U'];  //0 included as this means "not sure"

    const ATTENDANCE_CODES_SELECTABLE_BY_TEACHERS = [
        '0' => 'Absent',
        '1' => 'Present',
        'L' => 'Late'
    ];

    /* @property string $notes - virtual attribute, stored in Audit table */
    public $notes;

    public static function getAttendanceCodeForDisplay($code) {
        return self::ATTENDANCE_VALID_CODES[$code];
    }

    //NOTE: Adjust this function to support more than 2 daily registration periods
    public static function getCurrentPeriod() {
        $hour = date('H');
        return ($hour < 12) ? 1 : 2;
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
        //NOTE: Add to the attendance_code settings below if additional registration periods are added
        return [
            [['form_id', 'student_id', 'attendance_code_1', 'attendance_code_2', 'last_modified', 'last_modified_by'], 'required'],
            [['form_id', 'student_id', 'last_modified_by'], 'integer'],
          //  [['last_modified'], 'safe'],
            [['attendance_code_1', 'attendance_code_2'], 'string', 'max' => 1],
            [['notes'], 'string', 'max' => 2000],
            [['attendance_code_1', 'attendance_code_2'], 'in', 'range' => array_keys(self::ATTENDANCE_VALID_CODES)],
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
            'attendance_code_1' => '',
            'attendance_code_2' => '',
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

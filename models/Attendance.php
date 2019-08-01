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
    const ATTENDANCE_VALID_CODES = ['L', 'B', 'D', 'J', 'P', 'V', 'W', 'C', 'E',
        'H', 'I', 'M', 'R', 'S', 'T', 'G', 'N', 'O', 'U', 'X', 'Y', 'Z', '#', '0', '1'];

    //Per https://assets.publishing.service.gov.uk/government/uploads/system/uploads/attachment_data/file/818204/School_attendance_July_2019.pdf
    public static function getAttendanceCodeForDisplay($code) {
        switch ($code) {
            case 'L':
                return 'Late arrival before the register has closed';
            case 'B':
                return 'Off-site educational activity';
            case 'D':
                return 'Dual Registered - at another educational establishment';
            case 'J':
                return 'At an interview with prospective employers, or another educational establishment';
            case 'P':
                return 'Participating in a supervised sporting activity';
            case 'V':
                return 'Educational visit or trip';
            case 'W':
                return 'Work experience';
            case 'C':
                return 'Leave of absence authorised by the school';
            case 'E':
                return 'Excluded but no alternative provision made';
            case 'H':
                return 'Holiday authorised by the school';
            case 'I':
                return 'Illness (not medical or dental appointments)';
            case 'M':
                return 'Medical or dental appointments';
            case 'R':
                return 'Religious observance';
            case 'S':
                return 'Study leave';
            case 'T':
                return 'Gypsy, Roma and Traveller absence';
            case 'G':
                return 'Holiday not authorised by the school';
            case 'N':
                return 'Reason for absence not yet provided';
            case 'O':
                return 'Absent from school without authorisation';
            case 'U':
                return 'Arrived in school after registration closed';
            case 'X':
                return 'Not required to be in school';
            case 'Y':
                return 'Unable to attend due to exceptional circumstances';
            case 'Z':
                return 'Pupil not on admission register';
            case '#':
                return 'Planned whole or partial school closure';
            case '0':
                return '[Not yet recorded]';
            case '1':
                return 'Present in school';
            default:
                return null;
        }
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
            ['attendance_code', 'in', 'range' => self::ATTENDANCE_VALID_CODES],
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

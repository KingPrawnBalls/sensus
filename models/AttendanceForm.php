<?php


namespace app\models;


use http\Exception\RuntimeException;
use Yii;
use yii\base\Model;

class AttendanceForm extends Model
{
    public $isPresent = array();
    public $form_id;

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

    public function save() {

        //Prepare data which is same for every child to be saved in this batch
        $date = date(Yii::$app->params['dbDateFormat']);
        $period = self::getCurrentPeriod();
        $attendance_code = 1;
        $last_modified = date(Yii::$app->params['dbDateTimeFormat']);
        $last_modified_by = Yii::$app->user->id;

        if (!$last_modified_by)
            throw new RuntimeException('Couldn\'t get logged in user ID to save with Registration data.');

        //TODO transactions around this
        $success = true;

        foreach ($this->isPresent as $student_id => $value) {
            $attendanceRecord = new Attendance([
                'form_id' => $this->form_id,
                'student_id' => $student_id,
                'date' => $date,
                'period' => $period,
                'attendance_code' => $attendance_code,
                'last_modified' => $last_modified,
                'last_modified_by' => $last_modified_by,
            ]);
            if (!$attendanceRecord->save(false))
                $success = false;
        }
        return $success;

    }
}
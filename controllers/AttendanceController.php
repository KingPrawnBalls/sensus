<?php

namespace app\controllers;

use app\models\Audit;
use app\models\Form;
use app\models\Student;
use app\models\Visitor;
use DateTimeZone;
use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use Yii;
use app\models\Attendance;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * AttendanceController implements the CRUD actions for Attendance model.
 */
class AttendanceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user !== null && Yii::$app->user->identity->isSuperUser();
//                        }
                    ],
                    // everything else is denied by default
                ],
            ],
        ];
    }

    /**
     * Creates (or updates) multiple Attendance records for a Form (class).
     *
     * TODO use transactions
     *
     * @param integer $form_id of form (class)
     * @return mixed
     */
    public function actionCreate($form_id)
    {
        $isSavedOk = false;
        $form = Form::findOne($form_id);
        $students = $form->students;

        //Create Attendance Model for each student
        $attendanceModelArray = array();
        $date = date(Yii::$app->params['dbDateFormat']);

        foreach ($students as $student) {
            $queryParams = array(
                'form_id' => $form_id,
                'student_id' => $student->id,
                'date' => $date
            );
            $att = Attendance::findOne($queryParams);

            if ($att === null) {
                //Create and store a new record
                $att = new Attendance($queryParams);
                $att->attendance_code_1 = '0';
                $att->attendance_code_2 = '0';
                //NOTE: Add more to above to support more than 2 daily registration periods
                $att->last_modified = date(Yii::$app->params['dbDateTimeFormat']);
                $att->last_modified_by = Yii::$app->user->id;

                if (!$att->last_modified_by)
                    throw new RuntimeException('Couldn\'t get logged in user ID to save with Registration data.');

                if (!$att->save(false))
                    throw new RuntimeException('Couldn\'t save new Registration data for student with ID '.$att->student_id);
            }
            $attendanceModelArray[] = $att;
        }

        if (Yii::$app->request->isPost) {
            if (Attendance::loadMultiple($attendanceModelArray, Yii::$app->request->post()) && Attendance::validateMultiple($attendanceModelArray)) {

                $lastModifiedDt = date(Yii::$app->params['dbDateTimeFormat']);
                $lastModifiedByUserId = Yii::$app->user->id;
                $lastModifiedByUserName = Yii::$app->user->identity->full_name;


                foreach ($attendanceModelArray as $updatedAttendance) {
                    /* @var $updatedAttendance Attendance */

                    //Consider the record dirty if any of the DB attributes are modified, or if user added a note
                    $isDirty = count($updatedAttendance->getDirtyAttributes()) || $updatedAttendance->notes;
                    $oldAttribValues = [];
                    if ($isDirty) {
                        $oldAttribValues = $updatedAttendance->oldAttributes;
                        $updatedAttendance->last_modified = $lastModifiedDt;
                        $updatedAttendance->last_modified_by = $lastModifiedByUserId;
                    }
                    $isSavedOk = $updatedAttendance->save(false);

                    if ($isSavedOk) {
                        if ($isDirty) {
                            //If it was saved ok and the record was "dirty" (edited), write an audit log
                            $auditModel = new Audit();
                            $auditModel->table_name = Attendance::tableName();
                            $auditModel->foreign_key = $updatedAttendance->id;
                            $auditModel->data_1_old_val = $oldAttribValues['attendance_code_1'];
                            $auditModel->data_1_new_val = $updatedAttendance->attendance_code_1;
                            $auditModel->data_2_old_val = $oldAttribValues['attendance_code_2'];
                            $auditModel->data_2_new_val = $updatedAttendance->attendance_code_2;
                            $auditModel->user_notes = $updatedAttendance->notes;
                            $auditModel->modified_by = $lastModifiedByUserName;
                            $auditModel->modified_date_time = $lastModifiedDt;
                            $isSavedOk = $auditModel->save();
                        }
                    }

                    if (!$isSavedOk) {
                        break;  //Exit foreach early if either of the above writes to the DB failed.
                    }
                }

                if ($isSavedOk)
                    Yii::$app->session->setFlash('savedSuccessfully', 'Registration data for class saved OK.');
                else
                    Yii::$app->session->setFlash('saveFailed', 'Registration data could not be saved! Please retry the Save button...');
            }
        }

        if ($isSavedOk) {
            return $this->redirect(['site/index']);
        } else {
            $currentPeriod = Attendance::getCurrentPeriod();
            $isFullAttendanceInputRangeAllowed = Yii::$app->user->identity->isAdmin();

            return $this->render('create', [
                'currentPeriod' => $currentPeriod,
                'formattedAttendancePeriod' => Attendance::ATTENDANCE_PERIOD_LABELS_LONG[$currentPeriod],
                'attendanceModelArray' => $attendanceModelArray,
                'students' => $students,
                'form' => $form,
                'isFullAttendanceInputRangeAllowed' => $isFullAttendanceInputRangeAllowed,
            ]);
        }
    }


    public function actionExport($date_from, $date_to) {

        $dtFrom = strtotime($date_from); //e.g. Monday this week
        $dtTo = strtotime($date_to);

        if ($dtFrom === false || $dtTo === false) {
            throw new \yii\base\InvalidArgumentException('Invalid dates passed to Attendance Export action.');
        }

        $data = $this->getAttendanceReportData($dtFrom, $dtTo);

        return $this->render('export', [
            'attendanceDataProvider' => new ArrayDataProvider(['allModels'=>$data, 'pagination'=>false]),
        ]);
    }


    public function actionView($date_from, $date_to) {

        $dtFrom = strtotime($date_from); //e.g. Monday this week
        $dtTo = strtotime($date_to);

        if ($dtFrom === false || $dtTo === false) {
            throw new \yii\base\InvalidArgumentException('Invalid dates passed to Attendance View action.');
        }

        $data = $this->getAttendanceReportData($dtFrom, $dtTo);

        return $this->render('view', [
            'attendanceDataProvider' => new ArrayDataProvider(['allModels'=>$data, 'pagination'=>false])
        ]);
    }


    /**
     * Displays a report of today's attendance
     *
     * @return mixed
     */
    public function actionToday() {

        $today = date(Yii::$app->params['dbDateFormat']);

        $onPremisesAttendanceCodes = "'" . implode("','", Attendance::ATTENDANCE_CODES_ON_PREMISES) . "'";

        //NOTE: Adjust this SQL to support more than 2 daily registration periods
        $reportData = Yii::$app->db->createCommand(
            "SELECT f.name as form_name, s.last_name, s.first_name, 
                         a.attendance_code_1, a.attendance_code_2
                    FROM student s 
                    JOIN attendance a on s.id = a.student_id
                    JOIN form f on a.form_id = f.id
                    WHERE a.date = :date
                      AND (a.attendance_code_1 IN ($onPremisesAttendanceCodes)
                       OR  a.attendance_code_2 IN ($onPremisesAttendanceCodes))
                    ORDER BY f.name, s.last_name, s.first_name")
            ->bindValue(':date', $today)
            ->queryAll();

        /* @var $visitorsDataProvider \yii\data\ActiveDataProvider */
        $visitorsDataProvider = Visitor::findAllCheckedInNow();
        $visitorsDataProvider->pagination = false;

        return $this->render('today', [
            'attendanceDataProvider' => new ArrayDataProvider(['allModels'=>$reportData, 'pagination'=>false]),
            'visitorsDataProvider' => new ArrayDataProvider(['allModels' => $visitorsDataProvider->getModels(), 'pagination'=>false]),
        ]);
    }

    /**
     * @param $attendance_id
     * @param $column_label
     * @param $student_id
     * @return string
     */
    public function actionHistory($attendance_id, $column_label, $student_id)
    {
        $audits = Audit::find()->where( [
            'table_name' => Attendance::tableName(),
            'foreign_key' => $attendance_id,
        ]);

        $student = Student::findOne($student_id);

        return $this->renderAjax('_history', [
            'auditsDataProvider' => new ArrayDataProvider(['allModels' => $audits->all(), 'pagination'=>false]),
            'attendance_id' => $attendance_id,
            'column_label' => $column_label,
            'student_name' => $student->last_name . ' ' . $student->first_name,
        ]);
    }

    /**  Build an array of all the dates covered by the requested date range.  All the dates in reports SQL
     * have to be explicitly stated in the SQL for the PIVOT statement to work
     * @param $dtFrom int unix time stamp
     * @param $dtTo int unix time stamp
     * @return array of dates between $dtFrom and $dtTo formatted as strings for SQL
     * @throws \Exception
     */
    protected function getArrayOfAllDatesBetweenAandB($dtFrom, $dtTo) {

        $numberOfDays = date_diff(new \DateTime("@$dtFrom"), new \DateTime("@$dtTo"))->days;
        $numberOfDays++; //Add one because we want from $dtFrom to $dtTo inclusive
        $dateIterator = new \DateTime("@$dtFrom");
        $dateIterator->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $allDatesArray = [];
        $DB_DATE_FORMAT = Yii::$app->params['dbDateFormat'];
        for ($i=0; $i<$numberOfDays; $i++) {
            $allDatesArray[] = '[' . $dateIterator->format($DB_DATE_FORMAT) . ']';
            date_modify($dateIterator, '+1 day');
        }

        return $allDatesArray;
    }

    /**  Fetch (pivoted) attendance data for a date range from the database
     * @param int $dtFrom
     * @param int $dtTo
     * @return array
     * @throws \yii\db\Exception
     */
    protected function getAttendanceReportData(int $dtFrom, int $dtTo): array
    {
        $allDatesArray = $this->getArrayOfAllDatesBetweenAandB($dtFrom, $dtTo);
        $DB_DATE_FORMAT = Yii::$app->params['dbDateFormat'];

        //Gather all string variables needed to build SQL statement
        $allDatesString = implode(',', $allDatesArray);
        $startDateString = date($DB_DATE_FORMAT, $dtFrom);
        $endDateString = date($DB_DATE_FORMAT, $dtTo);
        $DELETED_FORM_STATUS = Form::STATUS_DELETED;

        //NOTE: Adjust this SQL if needed to support more than 2 daily registration periods
        $sql = "SELECT * FROM
                        (SELECT
                           f.name AS form_name,
                           s.id AS student_id,
                           s.last_name,
                           s.first_name,
                           a.date AS attendance_date,
                           CONCAT(a.id, '|', a.attendance_code_1, '|', a.attendance_code_2) AS attendance_tuple
                         FROM student s
                           JOIN attendance a ON s.id = a.student_id
                           JOIN form f ON a.form_id = f.id
                         WHERE a.date BETWEEN '$startDateString' AND '$endDateString'
                           AND f.status <> '$DELETED_FORM_STATUS'
                         ) AS sourceTable
                         PIVOT (
                           MAX(attendance_tuple)
                           FOR attendance_date
                            IN ($allDatesString)
                         ) AS pivotTable
                    ORDER BY form_name, last_name, first_name";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}

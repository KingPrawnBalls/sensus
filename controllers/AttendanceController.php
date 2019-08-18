<?php

namespace app\controllers;

use app\models\Form;
use app\models\Visitor;
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

                $lastModifiedValue = date(Yii::$app->params['dbDateTimeFormat']);
                $lastModifiedByValue = Yii::$app->user->id;

                foreach ($attendanceModelArray as $updatedAttendance) {
                    /* @var $updatedAttendance Attendance */
                    if (count($updatedAttendance->getDirtyAttributes())) {
                        $updatedAttendance->last_modified = $lastModifiedValue;
                        $updatedAttendance->last_modified_by = $lastModifiedByValue;
                    }
                    $isSavedOk = $updatedAttendance->save(false);

                    if (!$isSavedOk)
                        break;
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

    /** Convert attendance data rows returned from DB into a structure friendly for views, with no days missing:
     *   Array: {student_id} => ['last_name'=>'', 'first_name'=>'', 'date 1'=> [{attendance}=>'', 'date 2'=> [{attendance}=>'']]
     * @param &$data array - byRef
     * @param $dtFrom int unix time stamp
     * @param $dtTo int unix time stamp
     * @return int number of days the return date spans
     * @throws
     */
    protected function pivotDataForDisplay(&$data, $dtFrom, $dtTo) {

        $numberOfDays = date_diff(new \DateTime("@$dtFrom"), new \DateTime("@$dtTo"))->days;
        $numberOfDays++; //Add one because we want from $dtFrom to $dtTo inclusive

        $currentStudentId = null;
        $newData = array();
        $dateFormat = Yii::$app->params['dbDateFormat'];
        $schoolDaysOfWeek = Yii::$app->params['schoolDaysOfWeek'];
        $numberOfDailyRegistrationPeriods = Yii::$app->params['numberOfDailyRegistrationPeriods'];

        foreach ($data as $row) {
            if ($currentStudentId != $row['student_id']) {
                $newData[$row['student_id']] = [ 'last_name'=>$row['last_name'], 'first_name'=>$row['first_name'] ];
                $currentStudentId = $row['student_id'];

                //Make an array of empty attendance records for whole date range (in case of gaps in database)...
                $currentDate = new \DateTime("@$dtFrom");
                for ($i=0; $i<$numberOfDays; $i++) {

                    if (strpos($schoolDaysOfWeek, $currentDate->format('N')) !== FALSE) {  //If this date is a school day of the week, include it
                        $newData[$row['student_id']][$currentDate->format($dateFormat)] = array_fill(1, $numberOfDailyRegistrationPeriods, '0');
                    }
                    date_modify($currentDate, '+1 day');
                }
            }

            //Need the array_key_exists check to filter out any rogue data stored for Saturday/Sundays
            if (array_key_exists($row['date'], $newData[$row['student_id']])) {
                //Overwrite in the new array where data records exist in DB...
                for ($i=1; $i <= $numberOfDailyRegistrationPeriods; $i++) {
                    $newData[$row['student_id']][$row['date']][$i] = $row['attendance_code_'.$i];
                }
            }
        }
        $data = $newData;
        return $numberOfDays;
    }


    public function actionView($form_id) {

        //TODO parameterize the date start/end

        $dtFrom = strtotime('Monday this week');
        $dtTo = strtotime('now');

        $formName = Form::findOne($form_id)->name;

        //NOTE: Adjust this SQL to support more than 2 daily registration periods
        $data = Yii::$app->db->createCommand(
            "SELECT a.student_id, s.last_name, s.first_name, a.date,
                         a.attendance_code_1, a.attendance_code_2
                    FROM student s 
                    JOIN attendance a on s.id = a.student_id
                    WHERE a.date BETWEEN :d1 AND :d2
                      AND a.form_id = :form_id
                    ORDER BY s.last_name, s.first_name, a.date")
            ->bindValue(':form_id', $form_id)
            ->bindValue(':d1', date(Yii::$app->params['dbDateFormat'], $dtFrom))
            ->bindValue(':d2', date(Yii::$app->params['dbDateFormat'], $dtTo))
            ->queryAll();

        $numberOfDays = $this->pivotDataForDisplay($data, $dtFrom, $dtTo);

        return $this->render('view', [
            'attendanceDataProvider' => new ArrayDataProvider(['allModels'=>$data, 'pagination'=>false]),
            'formName' => $formName,
            'numberOfDays' => $numberOfDays,
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

}

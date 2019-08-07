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
        $form = Form::findOne($form_id);
        $students = $form->students;

        //Create Attendance Model for each student
        $attendanceModelArray = array();
        $date = date(Yii::$app->params['dbDateFormat']);
        $period = Attendance::getCurrentPeriod();
        foreach ($students as $student) {
            $queryParams = array(
                'form_id' => $form_id,
                'student_id' => $student->id,
                'date' => $date,
                'period' => $period,
            );
            $att = Attendance::findOne($queryParams);

            if ($att === null) {
                //Create and store a new record
                $att = new Attendance($queryParams);
                $att->attendance_code = '0';
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
                foreach ($attendanceModelArray as $updatedAttendance) {
                    /* @var $updatedAttendance Attendance */
                    $updatedAttendance->last_modified = date(Yii::$app->params['dbDateTimeFormat']);
                    $updatedAttendance->last_modified_by = Yii::$app->user->id;
                    $updatedAttendance->save(false);
                }
                $session = Yii::$app->session;
                $session->setFlash('savedSuccessfully', 'Registration data for class saved OK.');

            }
        }

        $fullAttendanceInputRangeAllowed = Yii::$app->user->identity->isAdmin();

        return $this->render('create', [
            'formattedAttendancePeriod' => Attendance::formatPeriodForDisplay($period),
            'attendanceModelArray' => $attendanceModelArray,
            'students' => $students,
            'form' => $form,
            'fullAttendanceInputRangeAllowed' => $fullAttendanceInputRangeAllowed,
        ]);
    }

    protected function pivotDataForDisplay($data, $dtFrom, $dtTo) {

        /** Convert attendance data rows returned from DB into this structure:
         *   Array: {student_id} => ['last_name'=>'', 'first_name'=>'', 'date 1'=> [{attendance}=>'', 'date 2'=> [{attendance}=>'']]
         */

        $numberOfDays = date_diff(new \DateTime("@$dtFrom"), new \DateTime("@$dtTo"))->days;
        $numberOfDays++; //Add one because we want from $dtFrom to $dtTo inclusive

        $currentStudentId = null;
        $newData = array();
        $dateFormat = Yii::$app->params['dbDateFormat'];

        foreach ($data as $row) {
            if ($currentStudentId != $row['student_id']) {
                $newData[$row['student_id']] = [ 'last_name'=>$row['last_name'], 'first_name'=>$row['first_name'] ];
                $currentStudentId = $row['student_id'];

                //Write into array empty attendance records for whole date range (in case of gaps in database)...
                $currentDate = new \DateTime("@$dtFrom");
                for ($i=0; $i<$numberOfDays; $i++) {
                    //TODO - next line needs to change if the number of registration periods each day ever changes
                    $newData[$row['student_id']][$currentDate->format($dateFormat)]
                        = [Attendance::ATTENDANCE_PERIOD_MORNING=>'0', Attendance::ATTENDANCE_PERIOD_AFTERNOON=>'0'];
                    date_modify($currentDate, '+1 day');
                }
            }

            //Find out which periods are recorded for this date/student combination...
            $periods =  explode(' ', $row['period']);
            $attendanceCodes = explode(' ', $row['attendance_code']);
            foreach ($periods as $idx=>$period) {
                //Overwrite in the new array where data records exist in DB...
                $newData[$row['student_id']][$row['date']][$period] = $attendanceCodes[$idx];
            }
        }
        return $newData;
    }


    public function actionView($form_id) {

        //TODO parameterize the date start/end

        $dtFrom = strtotime('Monday this week');
        $dtTo = strtotime('now');

        $formName = Form::findOne($form_id)->name;

        $rawResults = Yii::$app->db->createCommand(
            "SELECT a.student_id, s.last_name, s.first_name, a.date,
                         STRING_AGG (a.period, ' ') WITHIN GROUP (ORDER BY a.period) as period, 
                         STRING_AGG (a.attendance_code, ' ') WITHIN GROUP (ORDER BY a.period) as attendance_code
                    FROM student s 
                    JOIN attendance a on s.id = a.student_id
                    WHERE a.date BETWEEN :d1 AND :d2
                      AND a.form_id = :form_id
                    GROUP BY a.student_id, s.last_name, s.first_name, a.date
                    ORDER BY s.last_name, s.first_name, a.date")
            ->bindValue(':form_id', $form_id)
            ->bindValue(':d1', date(Yii::$app->params['dbDateFormat'], $dtFrom))
            ->bindValue(':d2', date(Yii::$app->params['dbDateFormat'], $dtTo))
            ->queryAll();

        $reportData = $this->pivotDataForDisplay($rawResults, $dtFrom, $dtTo);

        //TODO implement view
        return $this->render('view', [
            'attendanceDataProvider' => new ArrayDataProvider(['allModels'=>$reportData, 'pagination'=>false]),
            'formName' => $formName,
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

        $reportData = Yii::$app->db->createCommand(
            "SELECT f.name as form_name, s.last_name, s.first_name, 
                         STRING_AGG (a.period, ' ') WITHIN GROUP (ORDER BY a.period) as period, 
                         STRING_AGG (a.attendance_code, ' ') WITHIN GROUP (ORDER BY a.period) as attendance_code
                    FROM student s 
                    JOIN attendance a on s.id = a.student_id
                    JOIN form f on a.form_id = f.id
                    WHERE a.date = :date
                      AND a.attendance_code IN ($onPremisesAttendanceCodes)
                    GROUP BY f.name, s.last_name, s.first_name
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

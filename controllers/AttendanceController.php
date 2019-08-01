<?php

namespace app\controllers;

use app\models\Form;
use http\Exception\RuntimeException;
use Yii;
use app\models\Attendance;
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

}

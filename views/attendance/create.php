<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Attendance;

/* @var $this yii\web\View */
/* @var $attendanceModelArray Attendance[] */
/* @var $students app\models\Student[] */
/* @var $form app\models\Form */
/* @var $formattedAttendancePeriod string */
/* @var $isFullAttendanceInputRangeAllowed string */
/* @var $currentPeriod int */


/* @var $form ActiveForm */

$this->registerJs(
    "$('td.attendance input').keyup(function(){ $(this).val($(this).val().toUpperCase()); });"
);

$this->title = 'Register';

$attribNameForCurrentAttendancePeriod = 'attendance_code_' . $currentPeriod;

//Create an array for the dropdown box of attendance options, which shows the code as a prefix to the description
$attendanceCodeDropdownOptions = Attendance::ATTENDANCE_VALID_CODES;
array_walk($attendanceCodeDropdownOptions,
    function (&$item, $key) {
        $item = "$key - $item";
    }
);


?>
<div class="attendance-create">

    <h1><?= Html::encode($this->title) ?> <button class='btn btn-lg btn-success inactive'><?=$form->name?></button> <?= date(Yii::$app->params['longDateFormat'])?> (<?=$formattedAttendancePeriod;?>)</h1>

    <div class="container">
        <?php $form = ActiveForm::begin(); ?>

            <?php if (count($students) === 0) { ?>
                <div class="alert alert-warning" role="alert">
                    Whoops... no students are associated with this class!
                </div>
            <?php } ?>

            <table class="table table-striped">

                <?php foreach ($students as $idx=>$student) { ?>
                <tr>
                    <td class="student-name">
                        <?=$student->last_name?> <?=$student->first_name?>
                    </td>
                    <td class="attendance">
                        <?php
                            $isEditableByTeacher = in_array($attendanceModelArray[$idx]->getAttribute($attribNameForCurrentAttendancePeriod), array_keys(Attendance::ATTENDANCE_CODES_SELECTABLE_BY_TEACHERS));
                            if ($isFullAttendanceInputRangeAllowed) {
                                //Show drop down list for admins
                                echo $form->field($attendanceModelArray[$idx], "[$idx]$attribNameForCurrentAttendancePeriod")->dropDownList($attendanceCodeDropdownOptions);
                            } else if (!$isEditableByTeacher) {
                                //Show read only because it has been input already by an admin
                                echo '<b>'.$attendanceModelArray[$idx]->getAttribute($attribNameForCurrentAttendancePeriod).'</b>';
                                echo '<span class="attendance-desc">'.Attendance::getAttendanceCodeForDisplay($attendanceModelArray[$idx]->getAttribute($attribNameForCurrentAttendancePeriod)).'</span>';
                            } else {
                                //Show a radio group for teachers with limited options
                                echo  $form->field($attendanceModelArray[$idx], "[$idx]$attribNameForCurrentAttendancePeriod")->radioList(Attendance::ATTENDANCE_CODES_SELECTABLE_BY_TEACHERS);
                            }
                        ?>
                    </td>
                    <td><?= $form->field($attendanceModelArray[$idx], "[$idx]notes")->textarea() ?></td>
                </tr>
                <?php } ?>
            </table>

            <div>
                <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right']) ?>

                <?= Html::a('&lt; Cancel', '/site/index', ['class'=>'text-danger']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <?= $this->render('_explainCodes') ?>

    </div>


</div>

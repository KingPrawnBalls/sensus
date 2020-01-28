<?php

use yii\bootstrap4\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Attendance;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $attendanceModelArray Attendance[] */
/* @var $students app\models\Student[] */
/* @var $form app\models\Form */
/* @var $formattedAttendancePeriod string */
/* @var $formattedDate string */
/* @var $dateAsTimestamp int */
/* @var $isUserAuthorizedToSetAnyAttendanceCode bool */
/* @var $isUserAuthorizedToSetAnyDate bool */
/* @var $period int */



$this->registerJs(
    "$('td.attendance input').keyup(function(){ $(this).val($(this).val().toUpperCase()); });"
);
$this->registerJs(
        <<<JS
    $('#datePicker-kvdate').on('changeDate changeMonth changeYear', function(e) {
        $('#btnChangeDate').prop('disabled', e.date ? false : true);
    });
    $('#datePicker-kvdate').on('clearDate', function(e) {
        $('#btnChangeDate').prop('disabled', true);
    });
JS
);

$this->title = 'Register';

$attribNameForCurrentAttendancePeriod = 'attendance_code_' . $period;

//Create an array for the dropdown box of attendance options, which shows the code as a prefix to the description
$attendanceCodeDropdownOptions = Attendance::ATTENDANCE_VALID_CODES;
array_walk($attendanceCodeDropdownOptions,
    function (&$item, $key) {
        $item = "$key - $item";
    }
);

//"Invert" the valid school days of week to the invalid days, disable in calendar widget
$allowedDaysOfWeek = Yii::$app->params['schoolDaysOfWeek'];
$disallowedDaysOfWeek = '';
foreach (str_split("1234567") as $day) {
    if (strpos($allowedDaysOfWeek, $day) === false) {
        $disallowedDaysOfWeek .= ($day == '7' ? '0' : $day);  //In PHP Sunday=7, whereas in JS Sunday=0.
    }
}

?>
<div class="attendance-create">

    <h1><?= Html::encode($this->title) ?> <button class='btn btn-lg btn-success inactive'><?=$form->name?></button> <?= $formattedDate; ?> (<?=$formattedAttendancePeriod;?>)</h1>

    <div class="container">

        <?php if ($isUserAuthorizedToSetAnyDate) { ?>
            <form enctype="application/x-www-form-urlencoded" method="get">
                <input type="hidden" name="form_id" value="<?= $form->id ?>" />
                <div class="mt-5 mb-5 p-5 border border-primary rounded-lg">
                    <div class="clearfix">
                        <div class="p-1 d-inline-block" style="width:285px">

                            <?= DatePicker::widget([
                                //See https://demos.krajee.com/widget-details/datepicker#settings
                                'id' => 'datePicker',
                                'name' => 'set_att_date',
                                'value' => date('l d M Y', $dateAsTimestamp),
                                'buttonOptions' => ['class'=>'btn btn-primary'],
                                'removeButton' => false,
                                'pluginOptions' => [
                                    //See https://bootstrap-datepicker.readthedocs.io/en/latest/options.html
                                    'todayHighlight' => true,
                                    'todayBtn' => false,
                                    'format' => 'DD dd M yyyy',
                                    'daysOfWeekDisabled' => $disallowedDaysOfWeek,
                                    'maxViewMode' => 0,
                                    'startDate' => '-14d',
                                    'endDate' => '0d',
                                ]
                            ]); ?>
                        </div>

                        <span style="white-space: nowrap;">
                            <?php foreach (Attendance::ATTENDANCE_PERIOD_LABELS as $idx=>$label) { ?>
                            <div class="form-check form-check-inline">
                                <input <?= ($period == $idx ? 'checked' : '')?> class="form-check-input" type="radio" name="set_att_time_period" id="att_period<?=$idx?>" value="<?=$idx?>">
                                <label class="form-check-label" for="att_period<?=$idx?>"><?=$label?></label>
                            </div>
                            <?php } ?>
                        </span>
                        <button id="btnChangeDate" type="submit" class="btn btn-primary mb-1 float-right">Change Date &gt;</button>
                    </div>
                    <div class="font-weight-lighter small text-sm-right"><span class="text-danger"><em>Careful!</em></span> Changing date will lose any unsaved attendance set below.</div>
                </div>
            </form>
        <?php } ?>

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
                            if ($isUserAuthorizedToSetAnyAttendanceCode) {
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success float-right']) ?>

                <?= Html::a('&lt; Cancel', '/site/index', ['class'=>'text-danger']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <?= $this->render('_explainCodes') ?>

    </div>


</div>

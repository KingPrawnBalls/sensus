<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $attendanceModelArray app\models\Attendance[] */
/* @var $students app\models\Student[] */
/* @var $form app\models\Form */
/* @var $formattedAttendancePeriod string */
/* @var $fullAttendanceInputRangeAllowed string */

/* @var $form ActiveForm */

$this->registerJs(
    "$('td.attendance input').keyup(function(){ $(this).val($(this).val().toUpperCase()); });"
);

$this->title = 'Register';

//Create an array for the dropdown box of attendance options, which shows the code as a prefix to the description
$attendanceCodeDropdownOptions = \app\models\Attendance::ATTENDANCE_VALID_CODES;
array_walk($attendanceCodeDropdownOptions,
    function (&$item, $key) {
        $item = "$key - $item";
    }
);


?>
<div class="attendance-create">

    <h1><?= Html::encode($this->title) ?> <button class='btn btn-lg btn-success'><?=$form->name?></button> <?= date(Yii::$app->params['longDateFormat'])?> (<?=$formattedAttendancePeriod;?>)</h1>

    <?php
        if (Yii::$app->session->hasFlash('savedSuccessfully')) {
            echo Alert::widget([
                'options' => ['class' => 'alert-info'],
                'body' => Yii::$app->session->getFlash('savedSuccessfully'),
            ]);
        }
    ?>

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
                        <?=$student->first_name?> <?=$student->last_name?>
                    </td>
                    <td class="attendance">
                        <?php
                            if ($fullAttendanceInputRangeAllowed) {
                                //echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->textInput(['maxlength' => '1']);
                                echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->dropDownList($attendanceCodeDropdownOptions);
                            } else if (!is_numeric($attendanceModelArray[$idx]->attendance_code)) {
                                echo '<b>'.$attendanceModelArray[$idx]->attendance_code.'</b>';   //Read only for this user
                                echo '<span class="attendance-desc">'.\app\models\Attendance::getAttendanceCodeForDisplay($attendanceModelArray[$idx]->attendance_code).'</span>';
                            } else {
                                echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->checkbox();
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <div>
                <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right']) ?>

                <?= Html::a('&lt; Cancel', 'site/index', ['class'=>'text-danger']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div style="margin-top: 2em;">
            <a data-toggle="collapse" href="#codeDescriptions" role="button" aria-expanded="false" aria-controls="codeDescriptions">
                Show descriptions for codes &darr;
            </a>
        </div>
        <div class="collapse" id="codeDescriptions">
            <ul class="list-unstyled">
                <?php
                    foreach (\app\models\Attendance::ATTENDANCE_VALID_CODES as $code=>$desc) {
                        echo "<li><b style='font-family: monospace; padding-right: 10px;'>$code</b> $desc</li>";
                    }
                ?>
            </ul>
        </div>

    </div>


    <?php /*= $this->render('_form', [
        'model' => $model,
    ]) */?>

</div>

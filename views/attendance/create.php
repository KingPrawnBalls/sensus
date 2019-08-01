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
$this->params['breadcrumbs'][] = ['label' => 'Attendances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
                                echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->textInput(['maxlength' => '1']);
                            } else if (!is_numeric($attendanceModelArray[$idx]->attendance_code)) {
                                echo '<b>'.$attendanceModelArray[$idx]->attendance_code.'</b>';   //Read only for this user
                            } else {
                                echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->checkbox();
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <div class="row">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right']) ?>

                <?= Html::a('&lt; Cancel', 'site/index', ['class'=>'text-danger pull-left']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>


    <?php /*= $this->render('_form', [
        'model' => $model,
    ]) */?>

</div>

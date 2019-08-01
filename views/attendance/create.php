<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $attendanceModelArray app\models\Attendance[] */
/* @var $students app\models\Student[] */
/* @var $form app\models\Form */
/* @var $formattedAttendancePeriod string */

/* @var $form ActiveForm */


$this->title = 'Register';
$this->params['breadcrumbs'][] = ['label' => 'Attendances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="attendance-create">

    <h1><?= Html::encode($this->title) ?> <button class='btn btn-lg btn-success'><?=$form->name?></button> <?= date(Yii::$app->params['longDateFormat'])?> (<?=$formattedAttendancePeriod;?>)</h1>

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
                    <td style="text-align: right">
                        <?=$student->first_name?> <?=$student->last_name?>
                    </td>
                    <td style="text-align: left">
                        <?php
                            echo $form->field($attendanceModelArray[$idx], "[$idx]attendance_code")->checkbox();
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

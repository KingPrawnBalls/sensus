<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */
/* @var $formName string */
/* @var $numberOfDays int num of days spanned by the dataset */

$this->title = 'Attendance for  ' . $formName;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="attendance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_view', [
        'attendanceDataProvider' => $attendanceDataProvider,
    ]) ?>

    <p>Showing data for <?=$numberOfDays?> days.</p>

</div>
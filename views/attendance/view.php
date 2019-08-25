<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $date_from String param from query string */
/* @var $date_to String param from query string */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */

$this->title = 'Attendance Report';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="attendance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_view', [
        'attendanceDataProvider' => $attendanceDataProvider,
    ]) ?>

    <!--p>Showing data for dates: </p-->

    <?= $this->render('_explainCodes') ?>

    <a href="<?=Url::to(['attendance/export', 'date_from'=>$date_from, 'date_to'=>$date_to])?>"><span class="glyphicon glyphicon-file"></span> Download this report as Excel file</a>
</div>

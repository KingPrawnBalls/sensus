<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
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

</div>

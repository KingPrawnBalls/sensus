<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $attendanceDataProvider yii\data\ArrayDataProvider */
/* @var $visitorsDataProvider yii\data\ArrayDataProvider */

$this->title = 'On premises ' . date(Yii::$app->params['longDateFormat']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-today">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Students</h2>
    <?= GridView::widget([
        'dataProvider' => $attendanceDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'form_name',
            'last_name',
            'first_name',
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'attendance_code_1',
                'label'=>\app\models\Attendance::ATTENDANCE_PERIOD_LABELS[1],
                'value'=>function ($model, $key, $index, $column) {
                    $value = $model[$column->attribute];
                    return \app\models\Attendance::getAttendanceCodeForDisplay($value) ;
                },
            ),
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'attendance_code_2',
                'label'=>\app\models\Attendance::ATTENDANCE_PERIOD_LABELS[2],
                'value'=>function ($model, $key, $index, $column) {
                    $value = $model[$column->attribute];
                    return \app\models\Attendance::getAttendanceCodeForDisplay($value) ;
                },
            ),
        ],

    ]); ?>


    <h2>Visitors</h2>
    <?= GridView::widget([
        'dataProvider' => $visitorsDataProvider,
        'showOnEmpty' => false,
        'emptyText' => 'No visitors on site.',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'first_name',
            'last_name',
            'check_in_dt:time',
            'visiting',
        ],
    ]); ?>

</div>

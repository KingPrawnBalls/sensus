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
                'attribute'=>'period',
                'value'=>function ($model, $key, $index, $column) {
                    return ucfirst(\app\models\Attendance::formatPeriodForDisplay($model[$column->attribute]));
                },
            ),
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'attendance_code',
                'label'=>'Notes',
                'value'=>function ($model, $key, $index, $column) {
                    $code = $model[$column->attribute];
                    return \app\models\Attendance::ATTENDANCE_VALID_CODES[$code];
                },
            ),
        ],

    ]); ?>


    <h2>Visitors</h2>
    <?= GridView::widget([
        'dataProvider' => $visitorsDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'first_name',
            'last_name',
            'check_in_dt:time',
            'visiting',
        ],
    ]); ?>

</div>

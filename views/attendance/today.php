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
                    $spaceSeparatedPeriods = $model[$column->attribute];
                    $periodsArray = explode(' ', $spaceSeparatedPeriods);
                    array_walk($periodsArray, function(&$item) {
                        $item = ucfirst(\app\models\Attendance::formatPeriodForDisplay($item));
                    });
                    return implode(' / ', $periodsArray);
                },
            ),
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'attendance_code',
                'label'=>'Notes',
                'value'=>function ($model, $key, $index, $column) {
                    $spaceSeparatedValues = $model[$column->attribute];
                    $valuesArray = explode(' ', $spaceSeparatedValues);
                    array_walk($valuesArray, function(&$item) {
                        $item = \app\models\Attendance::ATTENDANCE_VALID_CODES[$item];
                    });
                    return implode(" / ", $valuesArray);
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

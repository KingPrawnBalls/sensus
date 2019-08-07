<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */
/* @var $formName string */
/* @var $numberOfDays int num of days spanned by the dataset */

$this->title = 'Attendance for  ' . $formName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Showing data for <?=$numberOfDays?> days</h2>

    <?php
        $columns = [];

        //Examine the first row in the model to determine the columns for the DataGrid
        if (count($attendanceDataProvider->allModels)>0) {
            foreach (reset($attendanceDataProvider->allModels) as $attrib => $value) {

                if ($attrib == 'last_name' || $attrib == 'first_name') {
                    $columns[] = $attrib;
                } else {
                    $columns[] = [
                        'class' => 'yii\grid\DataColumn',
                        'attribute'=>$attrib,
                        'value' => function ($model, $key, $index, $column) {
                            /* @var $column \yii\grid\DataColumn */
                            $attendancePeriods = $model[$column->attribute];
                            //TODO - needs to change if number of registration periods is ever increased
                            $am = $attendancePeriods[\app\models\Attendance::ATTENDANCE_PERIOD_MORNING];
                            $pm = $attendancePeriods[\app\models\Attendance::ATTENDANCE_PERIOD_AFTERNOON];
                            return ($am=='1'?'/':$am) . ' ' . ($pm=='1'?'\\':$pm);
                            //\app\models\Attendance::ATTENDANCE_VALID_CODES[$item];  //TODO use as tooltip ?
                        },
                    ];
                }
            }
        }

        echo GridView::widget([
        'dataProvider' => $attendanceDataProvider,
        'columns' => $columns,

    ]); ?>


</div>

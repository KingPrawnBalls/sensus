<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */
/* @var $formName string */
/* @var $numberOfDays int num of days spanned by the dataset */

$this->title = 'Attendance for  ' . $formName;
$this->params['breadcrumbs'][] = $this->title;

$dbDateFormat = Yii::$app->params['dbDateFormat'];
$shortDateFormat = Yii::$app->params['shortDateFormat'];

//https://getbootstrap.com/docs/4.3/components/tooltips/#example-enable-tooltips-everywhere
$this->registerJs(
    '$(function () { $(\'[data-toggle="tooltip"]\').tooltip() });'
);
?>
<div class="attendance-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
                        'label'=> date_create_from_format($dbDateFormat, $attrib)->format($shortDateFormat),
                        'format'=>'raw',
                        'value' => function ($model, $key, $index, $column) {
                            /* @var $column \yii\grid\DataColumn */
                            $attendancePeriods = $model[$column->attribute];
                            //TODO - needs to change if number of registration periods is ever increased
                            $am = $attendancePeriods[\app\models\Attendance::ATTENDANCE_PERIOD_MORNING];
                            $pm = $attendancePeriods[\app\models\Attendance::ATTENDANCE_PERIOD_AFTERNOON];
                            $amDisplay = ($am=='1' ? '&sol;'  : ($am=='0' ? '?' : $am ) );
                            $pmDisplay = ($pm=='1' ? '&bsol;' : ($pm=='0' ? '?' : $pm ) );
                            return '<span data-toggle="tooltip" data-placement="bottom" title="' .
                                \app\models\Attendance::ATTENDANCE_VALID_CODES[$am] .
                                '">' .
                                $amDisplay .
                                '</span> <span data-toggle="tooltip" data-placement="bottom" title="' .
                                \app\models\Attendance::ATTENDANCE_VALID_CODES[$pm] .
                                '">' .
                                $pmDisplay .
                                '</span>';
                        },
                    ];
                }
            }
        }

        echo GridView::widget([
        'dataProvider' => $attendanceDataProvider,
        'columns' => $columns,
        'summary' => 'Hover over or tap attendance codes to show explanation.',
    ]); ?>

    <p>Showing data for <?=$numberOfDays?> days.</p>

</div>

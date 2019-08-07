<?php

/* Displays a GridView containing attendance date for a date range */

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */

use yii\grid\GridView;
use \app\models\Attendance;
$dbDateFormat = Yii::$app->params['dbDateFormat'];
$shortDateFormat = Yii::$app->params['shortDateFormat'];

//https://getbootstrap.com/docs/4.3/components/tooltips/#example-enable-tooltips-everywhere
$this->registerJs(
    '$(function () { $(\'[data-toggle="tooltip"]\').tooltip() });'
);
$columns = [];

//Examine the first row in the model to determine the columns for the DataGrid
if (count($attendanceDataProvider->allModels)>0) {
    foreach (reset($attendanceDataProvider->allModels) as $attrib => $value) {

        $maybeDateColumn = date_create_from_format($dbDateFormat, $attrib);
        if ($maybeDateColumn === FALSE) {
            $columns[] = $attrib;
        } else {
            $columns[] = [
                'class' => 'yii\grid\DataColumn',
                'attribute'=>$attrib,
                'label'=> $maybeDateColumn->format($shortDateFormat),
                'format'=>'raw',
                'value' => function ($model, $key, $index, $column) {
                    /* @var $column \yii\grid\DataColumn */
                    $attendancePeriods = $model[$column->attribute];
                    //TODO - needs to change if number of registration periods is ever increased
                    $am = $attendancePeriods[Attendance::ATTENDANCE_PERIOD_MORNING];
                    $pm = $attendancePeriods[Attendance::ATTENDANCE_PERIOD_AFTERNOON];
                    $amDisplay = ($am=='1' ? '&sol;'  : ($am=='0' ? '?' : $am ) );
                    $pmDisplay = ($pm=='1' ? '&bsol;' : ($pm=='0' ? '?' : $pm ) );
                    return '<span data-toggle="tooltip" data-placement="bottom" title="' .
                        Attendance::ATTENDANCE_VALID_CODES[$am] .
                        '">' .
                        $amDisplay .
                        '</span> <span data-toggle="tooltip" data-placement="bottom" title="' .
                        Attendance::ATTENDANCE_VALID_CODES[$pm] .
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
]);

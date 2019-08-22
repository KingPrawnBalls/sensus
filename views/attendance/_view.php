<?php

/* Displays a GridView containing attendance date for a date range */

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */

use yii\grid\GridView;
use yii\helpers\Url;
use \app\models\Attendance;
use dimmitri\grid\ExpandRowColumn;
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
            $columnLabel = $maybeDateColumn->format($shortDateFormat);

            $columns[] = [
                'class' => ExpandRowColumn::class,  //See https://github.com/dimmitri/yii2-expand-row-column
                'attribute'=>$attrib,
                'column_id' => $attrib,
                'submitData' => function ($model, $key, $index) use ($attrib, $columnLabel) {
                    return [
                        'attendance_id' => $model[$attrib]['attendance_id'],
                        'column_label' => $columnLabel,
                        'student_id' => $key,
                    ];
                },
                'url' => Url::to(['history']),
                'label'=> $columnLabel,
                'format'=>'raw',
                'value' => function ($model, $key, $index, $column) {

                    if ($column instanceof \yii\grid\DataColumn) {
                        $attendancePeriods = $model[$column->attribute];
                    } else {
                        $attendancePeriods = $model[$column['attribute']];
                    }
                    //NOTE: - next line needs to change if the number of registration periods each day ever changes
                    $am = $attendancePeriods[1];
                    $pm = $attendancePeriods[2];
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
    'summary' => 'Hover over attendance codes to show explanation. Tap/click to see history of changes.',
]);

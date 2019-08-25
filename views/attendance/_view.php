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

$columns = [];

$data = $attendanceDataProvider->getModels();

//Examine the first row in the model to determine the columns for the DataGrid
if (count($data)) {
    foreach (reset($data) as $attrib => $value) {

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
                    $attendanceTuple = explode('|', $model[$attrib]);
                    return [
                        'attendance_id' => $attendanceTuple[0],
                        'column_label' => $columnLabel,
                        'student_id' => $model['student_id'],
                    ];
                },
                'url' => Url::to(['history']),
                'label'=> $columnLabel,
                'format'=>'raw',
                'value' => function ($model, $key, $index, $column) use ($attrib) {

                    $attendanceTuple = explode('|', $model[$attrib]);

                    if (count($attendanceTuple)>1) {
                        //NOTE: - next lines need to change if the number of registration periods each day ever changes
                        $am = $attendanceTuple[1];
                        $pm = $attendanceTuple[2];
                        $amDisplay = ($am == '1' ? '&sol;' : ($am == '0' ? '?' : $am));
                        $pmDisplay = ($pm == '1' ? '&bsol;' : ($pm == '0' ? '?' : $pm));
                        return '<span title="' . Attendance::ATTENDANCE_VALID_CODES[$am] . '">' .
                            $amDisplay .
                            '</span> <span title="' . Attendance::ATTENDANCE_VALID_CODES[$pm] . '">' .
                            $pmDisplay .
                            '</span>';
                    }
                },
            ];
        }
    }
}

echo GridView::widget([
    'dataProvider' => $attendanceDataProvider,
    'columns' => $columns,
    'summary' => 'Tap/click to see history of changes.  See link at foot of report to show attendance code descriptions.',
]);

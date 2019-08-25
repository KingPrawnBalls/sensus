<?php

/* Export an Excel spreadsheet as a file download, containing the data passed to this view from controller */

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */

use \moonland\phpexcel\Excel;

$data = $attendanceDataProvider->getModels();

if (count($data)) {

    $columns = [];
    $dbDateFormat = Yii::$app->params['dbDateFormat'];

        foreach (reset($data) as $attrib => $value) {

            $maybeDateColumn = date_create_from_format($dbDateFormat, $attrib);
            if ($maybeDateColumn === FALSE) {
                $columns[] = [
                    'attribute' => $attrib,
                    'header' => $attrib,
                ];
            } else {
                $columns[] = [
                    'attribute'=>$attrib,
                    'header' => $attrib,
                    'format'=>'raw',
                    'value' => function ($model, $context) use ($attrib) {
                        $attendanceTuple = $model[$attrib];
                        if ($attendanceTuple !== null) {
                            $attendanceTuple = explode('|', $attendanceTuple);
                            unset ($attendanceTuple[0]);  //Remove the attendance_id in element 0 which we dont include in report.
                            return implode($attendanceTuple);
                        } else {
                            return '';
                        }
                    },
                ];
            }
        }


    /* See https://www.yiiframework.com/extension/yii2-phpexcel */
    Excel::widget([
        'fileName' => "attendance.xlsx",
        'models' => $data,
        'mode' => 'export',
        'asAttachment' => true,
        'columns' => $columns,
    ]);
}

<?php

/* Export an Excel spreadsheet as a file download, containing the data passed to this view from controller */

/* @var $this yii\web\View */
/* @var $attendanceDataProvider \yii\data\ArrayDataProvider */
/* @var $formName String */

use \moonland\phpexcel\Excel;

$data = $attendanceDataProvider->getModels();
/* See https://www.yiiframework.com/extension/yii2-phpexcel */

if (count($data)) {
    $first_student_id = array_key_first($data);
    $columns = array_keys($data[$first_student_id]);
    $headers = array_combine($columns, $columns);

    Excel::widget([
        'fileName' => "$formName attendance.xlsx",
        'models' => $data,
        'mode' => 'export',
        'asAttachment' => true,
        'columns' => $columns,
        'headers' => $headers,
    ]);
}

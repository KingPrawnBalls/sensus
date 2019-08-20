<?php
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $auditsDataProvider \yii\data\ArrayDataProvider */
/* @var $attendance_id int */
/* @var $column_label string */
/* @var $student_name string */

?>

<?php Pjax::begin(['id' => "pjax-{$attendance_id}", 'enablePushState' => false]); ?>

<?= GridView::widget([
    'dataProvider' => $auditsDataProvider,
    'summary' => 'Attendance data change log for ' . $student_name . ', date ' . $column_label,
    'columns' => [
        'modified_date_time:datetime:Modified',
        'modified_by',
        'data_1_old_val:text:AM old value',
        'data_1_new_val:text:AM new value',
        'data_2_old_val:text:PM old value',
        'data_2_new_val:text:PM new value',
        'user_notes:text:Notes',
    ]
]) ?>

<?php Pjax::end(); ?>
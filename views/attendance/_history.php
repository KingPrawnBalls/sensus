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
    'summary' => 'Attendance data change log for <b>' . $student_name . '</b>, date <b>' . $column_label . '</b>',
    'emptyText' => 'No change log found for <b>' . $student_name . '</b> for date <b>' . $column_label . '</b>',
    'columns' => [
        'modified_date_time:datetime:Modified',
        'modified_by',
        [
            'label'=> 'AM',
            'format'=>'raw',
            'value' => function ($model, $key, $index, $column) {
                return $model->data_1_old_val . '&nbsp;&rarr;&nbsp;' . $model->data_1_new_val;
            }
        ],
        [
            'label'=> 'PM',
            'format'=>'raw',
            'value' => function ($model, $key, $index, $column) {
                return $model->data_2_old_val . '&nbsp;&rarr;&nbsp;' . $model->data_2_new_val;
            }
        ],
        'user_notes:text:Notes',
    ]
]) ?>

<?php Pjax::end(); ?>
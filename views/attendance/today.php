<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = 'On premises ' . date(Yii::$app->params['longDateFormat']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-today">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'form_name',
            'last_name',
            'first_name',
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'period',
                'value'=>function ($model, $key, $index, $column) {
                    return $model[$column->attribute] === \app\models\Attendance::ATTENDANCE_PERIOD_MORNING ? 'am' : 'pm';
                },
            ),
            array(
                'class' => 'yii\grid\DataColumn',
                'attribute'=>'attendance_code',
                'label'=>'Notes',
                'value'=>function ($model, $key, $index, $column) {
                    $code = $model[$column->attribute];
                    return \app\models\Attendance::ATTENDANCE_VALID_CODES[$code];
                },
            ),
        ],

    ]); ?>


</div>

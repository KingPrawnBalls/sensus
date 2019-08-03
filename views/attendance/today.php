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

    <?php // TODO transform the code to show the full desc ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'form_name',
            'last_name',
            'first_name',
            'period',
            'attendance_code',
        ],

    ]); ?>


</div>

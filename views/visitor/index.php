<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Today\'s Visitors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Check In Visitor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'first_name',
            'last_name',
            'check_in_dt:time',
            [
                'attribute' => 'checked_in_by',
                'value' => function ($model, $key, $index, $column) {
                    $user = $model->checkedInBy;
                    return $user->full_name;
                }
            ],
            'visiting',
            //'notes',
            [
                'attribute'=> 'check_out_dt',
                'format'=>'time',
                'visible'=>false,
            ],
            [
                'attribute' => 'checked_out_by',
                'value' => function ($model, $key, $index, $column) {
                    $user = $model->checkedOutBy;
                    return $user ? $user->full_name : null;
                },
                'visible' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => false,
                    'delete' => true,
                    'view' => true,
                ],

                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View details', Url::to(['visitor/view', 'id'=>$key]));
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('Check out', Url::to(['visitor/delete', 'id'=>$key]), [
                            'class' => 'text-danger',
                            'data' => [
                                'confirm' => "Are you sure you want to check out visitor $model->first_name $model->last_name?",
                                'method' => 'post',
                            ]
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>

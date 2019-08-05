<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Visitor */

$this->title = 'Visitor';
$this->params['breadcrumbs'][] = ['label' => 'Visitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="visitor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!$model->check_out_dt)
            echo Html::a('Check out', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => "Are you sure you want to check out visitor $model->first_name $model->last_name?",
                    'method' => 'post',
                ],
            ])
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'first_name',
            'last_name',
            'check_in_dt:datetime',
            [
                'attribute' => 'checked_in_by',
                'value' => function ($model) {
                    $user = $model->checkedInBy;
                    return $user->full_name;
                }
            ],
            'visiting',
            'notes',
            'check_out_dt:datetime',
            [
                'attribute' => 'checked_out_by',
                'value' => function ($model) {
                    $user = $model->checkedOutBy;
                    return $user ? $user->full_name : null;
                },
            ],
        ],
    ]) ?>

    <?= Html::a('&lt; Back', Url::to('visitor/index', true)) ?>

</div>

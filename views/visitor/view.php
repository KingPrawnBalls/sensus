<?php

use yii\helpers\Html;
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
        <?php  /* = Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'first_name',
            'last_name',
            'check_in_dt:datetime',
            'checked_in_by',
            'visiting',
            'notes',
            'check_out_dt:datetime',
            'checked_out_by',
        ],
    ]) ?>

</div>

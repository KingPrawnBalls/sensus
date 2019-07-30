<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use app\models\Form;

/* @var $this yii\web\View */
/* @var $data array app\models\Form */
/* @var $model Form */


$this->title = 'Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php foreach ($data as $model) {
        echo '<div class="btn-group" role="group">' . Html::a($model->name, '', ['class'=>'btn btn-lg btn-success']) . '</div>';
    } ?>


</div>

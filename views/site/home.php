<?php

/* @var $this yii\web\View */
/* @var $forms array */
/* @var $loginUrl String */
/* @var $model Form */

$this->title = 'Sensus School Management System';

use app\models\Form;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\BaseStringHelper;
use yii\helpers\Url;

/* @var $userIdentity User */
$userIdentity = \Yii::$app->user->identity;

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hi <?= BaseStringHelper::truncateWords($userIdentity->full_name, 1, '') ?>,</h1>

        <p class="lead"><?= strtolower($userIdentity->user_type) ?> at TKHSW. What do you want to do?</p>

        <hr class="my-4">

        <h3>Take<?=$userIdentity->isAdmin() ? '/adjust' : ''?>  the register</h3>

        <?php foreach ($forms as $model) {
            echo '<div class="btn-group" role="group">' . Html::a($model->name, Url::to(['attendance/create', 'form_id'=>$model->id]), ['class'=>'btn btn-lg btn-success']) . '</div>';
        } ?>

        <hr class="my-4">

        <h3>Run a report</h3>

        <div class="btn-group" role="group">
            <button class="btn btn-lg btn-primary" onclick="window.location='<?=$reportsUrl?>'">Today's attendance</button>
        </div>

        <div class="btn-group" role="group">
            <button class="btn btn-warning" onclick="window.location='<?=$reportsUrl?>'">Custom report</button>
        </div>

    </div>

</div>

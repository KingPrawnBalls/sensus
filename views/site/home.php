<?php

/* @var $this yii\web\View */
/* @var $loginUrl String */

$this->title = 'Sensus School Management System';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hi <?= \Yii::$app->user->identity->full_name ?></h1>

        <p class="lead">What do you want to do?</p>

        <p><a class="btn btn-lg btn-success" <a href="<?=$registerUrl?>">Take the register</a></p>
        <p><a class="btn btn-lg btn-success" <a href="<?=$reportsUrl?>">Run or print a report</a></p>
    </div>

</div>

<?php

/* @var $this yii\web\View */

$this->title = 'Sensus School Management System';

use yii\helpers\Url; ?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to Sensus</h1>

        <p class="lead">The School Management System for The King's House School, Windsor.</p>

        <p><a class="btn btn-lg btn-success" <a href="<?=Url::to(['site/login'])?>">Log in for access</a></p>
    </div>

</div>

<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <img src="/images/the-long-walk.jpg" style="max-width:40%; float:left; padding-right: 20px;" />
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <b>Sensus</b> is the School Management System developed at The King's House School, Windsor.
    </p>
    <p>
        It is still a work in progress.  Please send bug reports, feature requests, and any other comments
        to <b>pharding [at] kcionline [dot] org</b>
    </p>
    <p>
        Thank you!
    </p>

</div>

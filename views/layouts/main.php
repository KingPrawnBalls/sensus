<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap4\Alert;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\FontAwesomeAssets;

AppAsset::register($this);
FontAwesomeAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandImage' => '/images/KCS_Crown_book_type__WHT.png',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-expand-md navbar-dark',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right ml-auto'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],

            /*!Yii::$app->user->isGuest ? (
            ['label' => 'Register', 'url' => ['/site/register']]
            ) : '',

            !Yii::$app->user->isGuest ? (
            ['label' => 'Reports', 'url' => ['/site/reports']]
            ) : '',*/

            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->user_name . ')',
                    ['class' => 'btn btn-link nav-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),

            ['label' => 'About', 'url' => ['/site/about']],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= // Breadcrumbs::widget([ 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ])
        '' ?>
        <?php
        if (Yii::$app->session->hasFlash('savedSuccessfully')) {
            echo Alert::widget([
                'options' => ['class' => 'alert-info'],
                'body' => Yii::$app->session->getFlash('savedSuccessfully'),
            ]);
        }
        if (Yii::$app->session->hasFlash('saveFailed')) {
            echo Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => Yii::$app->session->getFlash('saveFailed'),
            ]);
        }
        ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="float-left">&copy; <?= date('Y') ?>.  All rights reserved.</p>

        <p class="float-right"><a href="http://kingshouseschool.org.uk">The King's House School, Windsor</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\widgets\CustomNavBar;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="main-header">
    <?php
    CustomNavBar::begin([
        'options' => [
            'class' => 'container',
        ],
        'renderInnerContainer' => false,
    ]);
    $menuItems = [
        ['label' => Html::img('/img/logo.png'), 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link'], 'options' => ['class' => 'list-item no-content']],
        ['label' => '<nav class="burger">
                            <a href="#" class="burger__button" id="burger-button">
                                <span class="burger__button__icon"></span>
                            </a>' .
            Nav::widget([
                'options' => ['class' => 'burger__menu'],
                'items' => [
                    ['label' => 'О нас', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link']],
                    ['label' => 'Связаться', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link']],
                ],
                'encodeLabels' => false,
            ])
            . '
                        </nav>', 'options' => ['class' => 'list-item no-content']],
        ['label' => 'О нас', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link'], 'options' => ['class' => 'list-item']],
        ['label' => 'Связаться', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link'], 'options' => ['class' => 'list-item']],
    ];
    echo Nav::widget([
        'options' => ['class' => 'menu'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    CustomNavBar::end();
    ?>


</header>
<main>
    <?= $content ?>
</main>
<footer>
    <div class="container-footer">
        <p>2019 © Game Insight. Все права сохранены.</p>
        <p>Лента новостей регламентируется соглашениями с правообладателями.</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

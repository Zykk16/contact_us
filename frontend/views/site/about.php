<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'О нас';
/** @var array $setting */
?>
<section class="about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="owl-carousel owl-theme">
        <?php foreach ($model as $item) {
            $res = $item; ?>
            <div class="item">
                <img src="<?= Yii::$app->params['back'] . '/img/' . $res['path'] . '/' . $res['name'] . '.' . $res['format'] ?>"
                     alt=""></div>
        <?php } ?>
    </div>
    <p><?= Html::encode($setting[0]['value'])?></p>
    <br>
</section>

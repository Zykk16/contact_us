<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Slider */

$this->title = 'Проставить флаг';
?>
<div class="slider-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

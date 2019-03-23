<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SliderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Галерея картинок';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="slider-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить картинку', ['site/upload'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
        <?php foreach ($model as $item) : ?>

            <div class="col-lg-3 col-md-4 col-6 thumb">
                <div class="flag"><a href="<?= Url::toRoute('slider/update') . '?id=' . $item['id'] ?>">Перейти</a></div>
                <a data-fancybox="gallery"
                   href="<?= '/img/' . $item['path'] . '/' . $item['name'] . '.' . $item['format'] ?>">
                    <img class="img-fluid"
                         src="<?= '/img/' . $item['path'] . '/' . $item['name'] . '.' . $item['format'] ?>" alt="">
                </a>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<!--<script-->
<!--        src="http://code.jquery.com/jquery-3.3.1.min.js"-->
<!--        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="-->
<!--        crossorigin="anonymous"></script>-->

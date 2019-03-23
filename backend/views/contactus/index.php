<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contactuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contactus-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Contactus', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_dep',
            'phone',
            [
                'attribute'=>'text',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'value'];
                },
            ],
            'date_create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered contactus',
            'width' => '10px',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

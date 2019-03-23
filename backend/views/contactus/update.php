<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Contactus */

$this->title = 'Update Contactus: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contactuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contactus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

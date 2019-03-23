<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Contactus */

$this->title = 'Create Contactus';
$this->params['breadcrumbs'][] = ['label' => 'Contactuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contactus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

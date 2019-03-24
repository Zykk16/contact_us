<?php
/**
 * Created by PhpStorm.
 * User: NIKITALIAN
 * Date: 22.03.2019
 * Time: 3:41
 */

use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'imageFile')->fileInput() ?>

<button class="btn btn-success">Добавить</button>

<?php ActiveForm::end() ?>

<?php if ($model->imageFile): ?>
    <img src="img/uploads/<?= $model->imageFile ?>" alt="">
<?php endif; ?>

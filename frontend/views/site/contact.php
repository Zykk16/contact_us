<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\widgets\MaskedInput;

$this->title = 'Связаться с нами';
?>
<section id="feed" class="feedback">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="container flex">
        <?php $form = ActiveForm::begin([
            'id' => 'contact-form',
            'options' => ['class' => 'flex-column validate'],
//            'fieldConfig' => [
//                'options' => [
//                    'tag' => false
//                ]
//            ]
        ]); ?>

        <?= $form->field($model, 'department')->dropDownList($model->getDep4Cont(), ['option' => ['class' => 'department']]) ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99',
            'options' => [
                'class' => 'phone',
                'id' => 'phone'
            ],
            'clientOptions' => [
                'clearIncomplete' => true
            ]
        ]); ?>

        <?= $form->field($model, 'text')->textarea(['cols' => '30', 'rows' => '10', 'id' => 'textarea', 'maxlength' => '400']) ?>

        <?= Html::submitButton('Отправить', ['class' => 'button', 'name' => 'contact-button']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>

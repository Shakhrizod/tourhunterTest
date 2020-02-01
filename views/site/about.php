<?php

/* @var $this yii\web\View */

use kartik\number\NumberControl;
use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Yii::$app->user->identity->username ?></h1>
    <p>balance: <?= Yii::$app->user->identity->balance?></p>
    <?php if (Yii::$app->user->identity->balance > -1000){?>
    <?php $form = \yii\widgets\ActiveForm::begin(['enableAjaxValidation' => true]) ?>
        <?= $form->field($model,    'username')->textInput() ?>
        <?=  $form->field($model, 'amount')->widget(NumberControl::classname(), [
            'maskedInputOptions' => [
                    'rightAlign' => false,
                'allowMinus' => false
            ],

        ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Transfer', ['class' => 'btn btn-primary', 'name' => 'transfer-button']) ?>
    </div>
    <?php \yii\widgets\ActiveForm::end()?>
    <?php }else{?>
        <p class="text-danger">Sorry you cannot transfer money. Your wallet -1000</p>
    <?php }?>
</div>

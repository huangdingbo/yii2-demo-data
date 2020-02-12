<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demo-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model,'instruction')->label('标题')?>

    <?=$form->field($model,'is_open')->dropDownList([
        '1' => '是',
        '0' => '否',
    ])->label('是否开启')?>

    <?=$form->field($model,'is_ignore_params')->dropDownList([
        '0' => '否',
        '1' => '是',
    ])->label('是否忽略参数比较')?>

    <div class="form-group">
        <?= \yii\bootstrap\Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

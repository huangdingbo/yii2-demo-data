<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DemoDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demo-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'unique_id') ?>

    <?= $form->field($model, 'action') ?>

    <?= $form->field($model, 'is_demo') ?>

    <?= $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'project') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'params') ?>

    <?php // echo $form->field($model, 'is_post') ?>

    <?php // echo $form->field($model, 'instruction') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

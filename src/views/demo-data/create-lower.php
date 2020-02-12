<?php
/**
 * @var $model backend\models\DemoData
 */
$this->title = '创建<' . $model->instruction . '>的下级';
$model->instruction = '';
use yii\bootstrap\ActiveForm; ?>
<div class="demo-data-config">

    <?php $form = ActiveForm::begin()?>

    <?=$form->field($model,'instruction')->label('标题')?>

    <div class="form-group">
        <?= \yii\bootstrap\Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end()?>
</div>
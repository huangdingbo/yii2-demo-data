<?php
/**
 * @var $model backend\models\DemoData
 */
$this->title = '替换<' . $model->instruction . '>的域名';

use yii\bootstrap\ActiveForm; ?>
<div class="demo-data-config">

    <?php $form = ActiveForm::begin()?>

    <?=$form->field($model,'domain')->label('将域名替换为:')?>

    <div class="form-group">
        <?= \yii\bootstrap\Html::submitButton('替换', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end()?>
</div>
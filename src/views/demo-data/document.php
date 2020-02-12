<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

use yii\bootstrap\ActiveForm;

$this->title = '演示数据配置文档';

?>
<div class="demo-data-config">

    <?php $form = ActiveForm::begin()?>

    <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(),[
        'clientOptions' => [
            'lang' => 'zh_cn',
            'plugins' => ['clips', 'fontcolor','imagemanager']
        ]
    ])?>

    <div class="form-group">
        <?= \yii\bootstrap\Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end()?>
</div>
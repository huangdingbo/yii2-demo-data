<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

use yii\bootstrap\ActiveForm;

$this->title = '演示数据基本信息';

?>
<div class="demo-data-config">

    <?php $form = ActiveForm::begin()?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php ActiveForm::end()?>
</div>
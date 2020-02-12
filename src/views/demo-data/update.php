<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

$this->title = '修改演示数据:' . $model->instruction;
?>
<div class="demo-data-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


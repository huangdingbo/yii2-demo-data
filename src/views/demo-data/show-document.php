<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

$this->title = '查看文档:' . $model->id;
?>
<div class="demo-data-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'content',
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>




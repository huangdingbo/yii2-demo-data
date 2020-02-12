<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

$this->title = '查看文档:' . $model->instruction;
?>
<div class="demo-data-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'instruction',
                'label' => '标题',
            ],
            'url',
            [
                'attribute' => 'global_params',
                'label' => '全局参数',
                'value' => function($model){
                    return $model->global_params == null ? '无' : $model->global_params;
                }
            ],
            [
                'attribute' => 'params_cache',
                'label' => '参数',
                'value' => function($model){
                    return $model->params_cache == null ? '无' : $model->params_cache;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->type == 1 ? 'POST' : 'GET';
                }
            ],
           'doc:ntext'
        ],
    ]) ?>

</div>




<?php

use dsj\adminuser\models\Adminuser;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

$this->title = '查看演示数据:' . $model->instruction;
?>
<div class="demo-data-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'instruction',
            [
                'attribute' => 'create_people',
                'value' => function($model){
                    $user = Adminuser::findOne(['id' => $model->create_people]);
                    return $user ? $user->nickname : '';
                }
            ],
            [
                'attribute' => 'update_people',
                'value' => function($model){
                    $user = Adminuser::findOne(['id' => $model->update_people]);
                    return $user ? $user->nickname : '';
                }
            ],
            'unique_id',
            [
                    'attribute' => 'is_open',
                    'value' => function($model){
                        return $model->is_open == 1 ? '是' : '否';
                    }
            ],
            [
                'attribute' => 'is_ignore_params',
                'value' => function($model){
                    return $model->is_open == 1 ? '是' : '否';
                }
            ],
            'url',
            'url_rule:ntext',
            [
                    'attribute' => 'type',
                    'value' => function($model){
                        return $model->type == 1 ? 'POST' : 'GET';
                    }
            ],
            'params_rule:ntext',
            'params_cache:ntext',
            'change_cache:ntext',
            'change_rule:ntext',
            'global_params:ntext',
            'data_rule:ntext',
            'data_cache:ntext',
            [
                    'attribute' => 'create_at',
                    'value' => function($model){
                        return date('Y-m-d H:i:s',$model->create_at);
                    }
            ],
            [
                'attribute' => 'update_at',
                'value' => function($model){
                    return date('Y-m-d H:i:s',$model->update_at);
                }
            ]
        ],
    ]) ?>

</div>




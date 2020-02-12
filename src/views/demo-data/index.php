<?php

use dsj\adminuser\models\Adminuser;
use dsj\components\assets\LayuiAsset;
use jianyan\treegrid\TreeGrid;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '演示数据配置';
$this->params['breadcrumbs'][] = $this->title;
LayuiAsset::register($this);
?>
<div class="menu-index">

    <div class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?= Html::button('创建顶级分组', ['class' => 'btn btn-success data-create-top','url' => Url::to(['create-top'])]) ?>
                    <?= Html::button('说明文档', ['class' => 'btn btn-info data-show-document','url' => Url::to(['show-document','id' => '演示数据配置.txt'])]) ?>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane">
                        <?= TreeGrid::widget([
                            'dataProvider' => $dataProvider,
                            'keyColumnName' => 'id', //ID
                            'parentColumnName' => 'pid', //父ID
                            'parentRootValue' => '0', //first parentId value
                            'pluginOptions' => [
                                'initialState' => 'expanded', //expanded 展开 ，collapsed 收缩
                            ],
                            'options' => ['class' => 'table table-hover'],
                            'columns' => [
                                [
                                    'attribute' => 'instruction',
                                    'format' => 'raw',
                                    'label' => '标题',
                                    'headerOptions' => ['class' => 'col-md-4'],
                                    'value' => function ($model, $key, $index, $column){
                                        $str = Html::tag('span', $model->instruction, [
                                            'class' => 'm-l-sm'
                                        ]);
                                        if ($model->pid == 0){
                                            $str .= \Yii\helpers\Html::a(' <i class="glyphicon glyphicon-wrench"></i>', ['batch-replace', 'id' => $model['id']], [
                                                'title' => Yii::t('yii','替换域名'),
                                                'aria-label' => Yii::t('yii','替换域名'),
                                                'data-toggle' => 'modal',
                                                'data-target' => '#create-modal',
                                                'class' => 'data-batch-replace',
                                                'data-id' => $key,
                                            ]);
                                        }
                                        $str .= \Yii\helpers\Html::a(' <i class="glyphicon glyphicon-circle-arrow-down"></i>', ['create-lower', 'pid' => $model['id']], [
                                            'title' => Yii::t('yii','添加下级分组'),
                                            'aria-label' => Yii::t('yii','添加下级分组'),
                                            'data-toggle' => 'modal',
                                            'data-target' => '#create-modal',
                                            'class' => 'data-create-lower',
                                            'data-id' => $key,
                                        ]);
                                        $str .= \Yii\helpers\Html::a('  <i class="glyphicon glyphicon-plus-sign"></i>', ['create', 'pid' => $model['id']], [
                                            'title' => Yii::t('yii','创建'),
                                            'aria-label' => Yii::t('yii','创建'),
                                            'data-toggle' => 'modal',
                                            'data-target' => '#create-modal',
                                            'class' => 'data-create',
                                            'data-id' => $key,
                                        ]);
                                        return $str;
                                    }
                                ],
                                [
                                    'attribute' => 'update_people',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'col-md-1'],
                                    'value' => function ($model, $key, $index, $column){
                                        $user = Adminuser::findOne(['id' => $model->update_people]);
                                        return $model->is_parent == 1 ? '' : ($user ? $user->nickname : '');
                                    }
                                ],
                                [
                                    'attribute' => 'unique_id',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'col-md-1'],
                                    'value' => function ($model, $key, $index, $column){
                                        return $model->unique_id == null ? '' : $model->unique_id;
                                    }
                                ],
                                [
                                    'attribute' => 'type',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'col-md-1'],
                                    'value' => function ($model, $key, $index, $column){
                                        return $model->type == 1 ? "POST" : ($model->is_parent == 1 ? '' : 'GET');
                                    }
                                ],
                                [
                                    'attribute' => 'is_open',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'col-md-1'],
                                    'value' => function ($model, $key, $index, $column){
                                        return  $model->is_open == 1 ? ($model->is_parent == 1 ? '' : '是') : '否';
                                    }
                                ],
                                [
                                    'attribute' => 'is_ignore_params',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'col-md-1'],
                                    'value' => function ($model, $key, $index, $column){
                                        return $model->is_ignore_params == 1 ? "是" : ($model->is_parent == 1 ? '' : '否');
                                    }
                                ],
                                [
                                    'header' => "操作",
                                    'headerOptions' => ['class' => 'col-md-3'],
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=> '{view} {request} {update} {status} {doc} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return $model->is_parent == 1 ? '' : Html::button('查看', [
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-primary btn-sm data-view',
                                                'url' => $url,
                                            ]);
                                        },
                                        'request' => function ($url, $model, $key) {
                                            return $model->is_parent ==1 ? '' : Html::button('配置', [
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-success btn-sm data-request',
                                                'url' => $url,
                                            ]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return $model->is_parent == 1 ? '' : Html::button('编辑', [
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-warning btn-sm data-update',
                                                'url' => $url,
                                            ]);
                                        },
                                        'status' => function ($url, $model, $key) {
                                            $showName = $model->is_open == 1 ? '禁用' : '启用';
                                            $confirmName = $model->is_open == 1 ? '确定要禁用吗？' : '确定要启用吗？';
                                            $className = $model->is_open == 1 ? 'btn btn-default btn-sm' : 'btn btn-success btn-sm';
                                            return $model->is_parent == 1 ? '' : Html::button($showName,[
                                                'class' => $className . ' data-status',
                                                'data-pjax' => '0',
                                                'url' => $url,
                                                'confirmName' => $confirmName
                                            ]);
                                        },
                                        'doc' => function ($url, $model, $key) {
                                            return $model->is_parent == 1 ? '' : Html::button('文档', [
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-info btn-sm data-doc',
                                                'url' => $url,
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::button('删除',[
                                                'class' => 'btn btn-danger btn-sm data-delete',
                                                'data-pjax' => '0',
                                                'url' => $url,
                                            ]);
                                        },
                                    ],
                                ],
                            ]
                        ]); ?>
                        <?php
                        $webPath = Yii::getAlias('@web');
                        $js = <<<JS
    //查看
    $('.data-view').on('click', function () {
        let config = {
             'type': 2,
              'title': '查看',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //配置
    $('.data-request').on('click', function () {
        let config = {
             'type': 2,
              'title': '配置',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //编辑
    $('.data-update').on('click', function () {
        let config = {
             'type': 2,
              'title': '编辑',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //删除
    $('.data-delete').on('click', function () {
            let url = $(this).attr('url');
    layui.config({
        base:'$webPath/layui/src/layuiadmin/' //静态资源所在路径
    }).use(['layer'],function() {
        var layer = layui.layer;
        layer.confirm("你确定要删除配置吗?", {icon: 7, title:'提示',skin:'layui-layer-lan'}, function(index){
               $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
			    });
              layer.close(index);
        });
});
    });
    //创建
    $('.data-create').on('click', function () {
      let config = {
             'type': 2,
              'title': '创建',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('href');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //创建顶级分组
    $('.data-create-top').on('click', function () {
      let config = {
             'type': 2,
              'title': '创建顶级分组',
              'area': ['800px','350px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //创建下级
    $('.data-create-lower').on('click', function () {
      let config = {
             'type': 2,
              'title': '创建下级',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('href');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //字段说明
    $('.data-doc').on('click', function () {
      let config = {
             'type': 2,
              'title': '字段说明',
              'area': ['900px','600px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //状态
    $('.data-status').on('click', function () {
            let url = $(this).attr('url');
            let confirmName = $(this).attr('confirmName');
    layui.config({
        base:'$webPath/layui/src/layuiadmin/' //静态资源所在路径
    }).use(['layer'],function() {
        var layer = layui.layer;
        layer.confirm(confirmName, {icon: 7, title:'提示',skin:'layui-layer-lan'}, function(index){
               $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
			    });
              layer.close(index);
        });
});
    });
    //说明文档
    $('.data-show-document').on('click', function () {
             let config = {
             'type': 2,
              'title': '说明文档',
              'area': ['1000px','600px'],
              'shadeClose': true,
              'maxmin':true
        };
        config.content = $(this).attr('url');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
    //替换域名
     $('.data-batch-replace').on('click', function () {
             let config = {
              'type': 2,
              'title': '替换域名',
              'area': ['600px','350px'],
              'shadeClose': true,
        };
        config.content = $(this).attr('href');
          layui.config({
                base: '$webPath/layui/src/layuiadmin/'
            }).use('layer', function(){
                var layer = layui.layer;
                layer.open(config);
            });
    });
JS;
                        $this->registerJs($js);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

use dsj\components\assets\JsonEditorAsset;
use dsj\components\assets\LayuiAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
LayuiAsset::register($this);
JsonEditorAsset::register($this);
$this->title = '第一步:请求接口';

?>
<div class="demo-data-request">

    <?php $form = ActiveForm::begin()?>

    <?=$form->field($model,'instruction')->label('说明')?>

    <?=$form->field($model,'url')->label('请求地址')?>

    <?=$form->field($model,'params_cache')->textarea(['rows' => 6,'style' => ['resize' => 'none']])
        ->label('请求参数' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>')?>

    <?=$form->field($model,'type')->dropDownList([
        '1' => 'POST',
        '0' => 'GET',
    ])->label('请求类型')?>

    <div class="form-group">
        <?= Html::submitButton('请求', ['class' => 'btn btn-warning']) ?>
        <?= Html::a('跳过', ['config','id' => $model->id],['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end()?>

    <?php
    $css = <<<CSS
        #tree_editor {
            width: 500px;
            height: 448px;
        }
        #json_editor {
            width: 500px;
            height: 448px;
        }
        #center {
            width: 62px;
            /*margin-left: 100px;*/
        }
        #right{

        }
        #left{
            transform: rotate(180deg);
        }
        #main{
            display: flex;
            align-items: center;
        }
CSS;
    $this->registerCss($css);

    $webPath = Yii::getAlias('@web');
    $js = <<<JS
  layui.config({
        base:'$webPath/layui/src/layuiadmin/' //静态资源所在路径
    }).use(['layer'],function() {
        var layer = layui.layer;
         function showTips(id,content,tips=1,event1='focus',event2='blur') {
             $(id).on(event1,function() {
                let index = layer.open({
                    type:4,
                    shade:0,
                    tips:tips, //方向
                    content: [content, id]
            });
                $(id).on(event2,function() {
                    layer.close(index);
                })
            });
         }
         //说明
         showTips('#demodata-instruction','输入演示数据说明，告诉别人你在做什么...');
         //url
         showTips('#demodata-url','输入请求的地址，可以是现有的接口地址或者用api生成器生成出来的地址...');
         //请求参数
         showTips('#demodata-params_cache','以标准的json格式输入请求参数，做填写此项时，你得确认你要添加的演示数据有没有参数，参数具体是什么，如果是现有的接口，肯定是有接口文档的，查阅一下...');
         
          $('.data-json').on('click',function() {
                let grandFather = $(this).parents()[1];
                let value = $(grandFather).find('textarea').text();
                let index = layer.open({
                  'type': 1,
                  'title': 'json工具',
                  'maxmin':true,
                  'area': ['800px','520px'],
                  'content' : '<div id="main">' +
                               '<div id="tree_editor"></div>' + 
                               '<div id="json_editor"></div>' + 
                               '</div>' + 
                               '<div id="footer_button">' +
                               '<a href="#" class="btn btn-primary btn-sm btn-block" id="save">保存</a>' +
                               '</div>',
                  'shadeClose': true,
                  success:function() {
                        const  editor1 = new JSONEditor(document.getElementById('tree_editor'), {
                        onChangeText: function (jsonString) {
                            editor2.set(JSON.parse(jsonString))
                            }
                        });               
                        // create editor 2
                        const  editor2 = new JSONEditor(document.getElementById('json_editor'),{
                             modes: ['code', 'text', 'form', 'view'],
                             mode: 'code',
                             ace: ace,
                             onChangeText: function (jsonString) {
                                editor1.set(JSON.parse(jsonString))
                            }
                        });                
                        let json = {a:1};
                        if (value){
                            json = JSON.parse(value);
                        }
                        editor1.set(json);
                        editor2.set(json);
                        
                        $('#save').on('click',function() {
                             let res = JSON.stringify(editor1.get(), null, 2);
                             if (res == '{}'){
                                 res = '';
                             }
                             $(grandFather).find('textarea').text(res)    
                             layer.close(index)                                               
                        })
                    },
                });
          })
    })
JS;
    $this->registerJs($js);
    ?>


</div>

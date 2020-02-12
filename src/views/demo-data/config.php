<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DemoData */

use dsj\components\assets\JsonEditorAsset;
use dsj\components\assets\LayuiAsset;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
LayuiAsset::register($this);
JsonEditorAsset::register($this);
$this->title = '第二步:配置规则';

?>
<div class="demo-data-request">

    <?php $form = ActiveForm::begin()?>

    <?=$form->field($model,'instruction')->label('说明')?>

    <?=$form->field($model,'url')->label('请求地址')?>

    <?=$form->field($model,'type')->dropDownList([
        '1' => 'POST',
        '0' => 'GET',
    ])->label('请求类型')?>

    <?=$form->field($model,'url_rule')->textarea(['rows' => 4,'style' => ['resize' => 'none']])
        ->label('Url规则' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>')?>

    <?php
    if ($model->is_ignore_params == '1'){
        echo $form->field($model,'change_rule')->textarea(['rows' => 4,'style' => ['resize' => 'none']])
            ->label('数据变化规则' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>');
    }else{
        echo $form->field($model,'params_rule')->textarea(['rows' => 4,'style' => ['resize' => 'none']])
            ->label('参数规则' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>');
    }
    ?>

    <?=$form->field($model,'global_params')->textarea(['rows' => 4,'style' => ['resize' => 'none']])
        ->label('全局参数' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>')?>

    <?=$form->field($model,'data_rule')->textarea(['rows' => 20,'style' => ['resize' => 'none']])
        ->label('数据规则' . ' <a href="#"  class="btn btn-primary btn-xs data-json">json工具</a>');?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?= Html::a('上一步', ['request','id' => $model->id],['class' => 'btn btn-warning']) ?>
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
         showTips('#demodata-instruction','或许前面一步你已经填写了说明了，在这里你可以修改或者使用上一步填写的内容...',);
         //url
         showTips('#demodata-url','或许前面一步你已经填写了请求地址了，在这里你可以修改或者使用上一步填写的内容...');
         //全局参数
         showTips('#demodata-global_params','全局参数的作用是在下面的规则中你可以直接用这里面配置的值或进行算术运算，如果你确定要使用全局参数，那么请以标准的json格式输入全局参数，用不着就留空...');
         //url规则
         showTips('#demodata-url_rule','url规则是用来解决同一个接口，参数不同，返回的数据结构不同的问题，比如同一个接口type=1，返回的数数量相关信息，type=2是金额相关信息，这是为了区分type不同的情况' +
          '就可以在url规则里面type=1或者type=2,此时值针对你配置的type生效，如果你在这里配置来type=1的情况，有想配置type=2的情况，那你不得不再创建一条演示数据，url规则配成type=2，不知道我说清楚没有...');
         //参数规则
         showTips('#demodata-params_rule','在不忽略参数变化的时候就要填写参数规则，用于跟用户的请求参数做比对，只要用户请求参数符合此项的规则才会走到你配置的演示数据规则里面去，否则还是会走正常的逻辑...');
         //数据规则
          showTips('#demodata-data_rule','如果你前一步添加来请求地址，并且地址是正确的，有放回值的，那么这里就会给你构建出请求地址所返回值的规则作为默认规则，你可以删除写你自己的规则或者修改默认的规则，如果是' +
           '现有的接口，建议不要更改数据的结构，只修改每个值的规则，如果改了接口前端拿不到值是要报错的...');
          
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

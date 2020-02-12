<?php

namespace dsj\demoData\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%demo_data}}".
 *
 * @property int $id
 * @property int $pid
 * @property string $unique_id Url加密后的唯一id
 * @property string $url url
 * @property string $url_rule url规则
 * @property string $type 请求类型
 * @property string $global_params 全局参数
 * @property string $params_cache 参数
 * @property string $params_rule 参数规则
 * @property resource $data_cache 数据
 * @property string $data_rule 数据规则
 * @property string $change_cache 数据变化规则
 * @property string $change_rule 数据变化规则缓存
 * @property string $instruction 简介
 * @property string $doc 文档
 * @property int $is_open 是否开启
 * @property int $is_ignore_params 是否忽略参数比较
 * @property int is_parent
 * @property int $create_at
 * @property int $create_people
 * @property int $update_people
 * @property int $update_at
 */
class DemoData extends ActiveRecord
{
    public $domain;
    public $doc;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_demo_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['pid'], 'required'],
//            [['unique_id','url'], 'required'],
            [['pid', 'create_at', 'update_at','create_people','update_people'], 'integer'],
            [['params_cache', 'params_rule', 'data_cache', 'data_rule', 'change_cache', 'change_rule','url','url_rule'], 'string'],
            ['global_params','string','max' => 500],
            [['unique_id'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 10],
            [['instruction'], 'string', 'max' => 200],
            [['is_open', 'is_ignore_params','is_parent'], 'integer', 'max' => 1],
            [['params_cache','url_rule','params_rule','data_rule','global_params'], 'validateIsJson'],
            ['unique_id','unique'],
            [['doc','domain'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'unique_id' => 'UniqueID',
            'url' => '请求地址',
            'url_rule' => '请求地址规则',
            'type' => '请求类型',
            'global_params' => '全局参数',
            'params_cache' => '参数规则数据缓存',
            'params_rule' => '参数规则',
            'data_cache' => '数据缓存',
            'data_rule' => '数据规则',
            'change_cache' => '数据变化规则',
            'change_rule' => '数据变化规则缓存',
            'instruction' => '简介',
            'is_open' => '是否开启',
            'is_ignore_params' => '忽略参数比较',
            'create_people' => '创建人',
            'update_people' => '最后修改人',
            'create_at' => '创建时间',
            'update_at' => '最后修改时间',
            'doc' => '字段说明',
        ];
    }

    public function  getFirstStrError(){
        $msg = '';
        foreach ($this->getErrors() as $item){
            $msg = $item[0];
            break;
        }
        return $msg;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            if ($insert){
                $this->create_at = time();
                $this->update_at = time();
                $this->create_people = Yii::$app->user->id;
                $this->update_people = Yii::$app->user->id;
            }else{
                $this->update_at = time();
                $this->update_people = Yii::$app->user->id;
            }
            return true;
        }

        return false;
    }

    public function validateIsJson($attribute){
        if (!$this->hasErrors()){
            try{
                Json::decode($this->$attribute);
            }catch (\Exception $e){
                $this->addError($attribute,'Json格式不正确');
                return false;
            }
        }

        return true;
    }
}

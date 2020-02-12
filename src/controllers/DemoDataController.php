<?php

namespace dsj\demoData\controllers;

use dsj\components\controllers\WebController;
use dsj\components\models\DocumentForm;
use dsj\components\widgets\layer\Layer;
use dsj\demoData\models\DemoData;
use dsj\demoData\models\DemoDataSearch;
use linslin\yii2\curl\Curl;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DemoDataController implements the CRUD actions for DemoData model.
 */
class DemoDataController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DemoData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemoDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemoData model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $model->url_rule = $model->url_rule == null ? '无' : Json::encode(Json::decode($model->url_rule,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->data_rule = $model->data_rule == null ? '无' : Json::encode(Json::decode($model->data_rule,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->data_cache = $model->data_cache == null ? '无' : Json::encode(Json::decode($model->data_cache,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->params_cache = $model->params_cache == null ? '无' : Json::encode(Json::decode($model->params_cache,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->params_rule = $model->params_rule == null ? '无' : Json::encode(Json::decode($model->params_rule,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->change_cache = $model->change_cache == null ? '无' : Json::encode(Json::decode($model->change_cache,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->change_rule = $model->change_rule == null ? '无' : Json::encode(Json::decode($model->change_rule,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $model->global_params = $model->global_params == null ? '无' : Json::encode(Json::decode($model->global_params,true), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new DemoData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pid)
    {
        $model = new DemoData();
        if ($model->load(Yii::$app->request->post())) {
            $model->pid = $pid;
            $model->is_parent = 0;
            if ($model->save()){
                $this->redirectParent(['index']);
            }else{
                echo Layer::widget([
                    'type' => 5,
                    'content' => $model->getFirstStrError(),
                    'time' => '3000',
                    'icon' => 2,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DemoData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                $this->redirectParent(['index']);
            }else{
                echo Layer::widget([
                    'type' => 5,
                    'content' => Json::encode($model->getErrors()),
                    'time' => '3000',
                    'icon' => 2,
                ]);
            }
//            $this->redirectParent(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     *
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (DemoData::findOne(['pid' => $id])){
            Yii::$app->session->setFlash('danger','该分组下含有子集，不能删除!');
            return $this->redirect(['index']);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DemoData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemoData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemoData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     * 请求
     */
    public function actionRequest($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){
            if ($model->validate()){
                $params = Json::decode($model->params_cache);
                if (!$params){
                    $params = [];
                }
                if ($model->type == '0'){
                    $data = (new Curl())->setGetParams($params)->get($model->url);
                }else{
                    $data = (new Curl())->setPostParams($params)->post($model->url);
                }
                //生成数据规则
                $data = $this->dealRequestData(Json::decode($data));
                if (!$data){
                    throw new Exception('请求数据为空，请检查!!!');
                }
                RulesServer::encodeRules($data);
                $model->data_rule = Json::encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

                //生成参数规则
                if ($params){
                    RulesServer::encodeRules($params);
                    $model->params_rule = Json::encode($params,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                }
                if ($model->save()){
                    return $this->redirect(['config','id' => $id]);
                }
            }else{
                return $this->render('request',['model' => $model]);
            }
        }

        if ($model->params_cache){
            $model->params_cache = Json::decode($model->params_cache);
            $model->params_cache = Json::encode($model->params_cache,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        return $this->render('request',['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * 配置
     */
    public function actionConfig($id){

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())){
            if ($model->validate()){
                $url = $model->url;
                if ($model->url_rule){
                    $urlRuleArr = Json::decode($model->url_rule);
                    RulesServer::decodeRules($urlRuleArr);
                    $urlArr = explode('?',$model->url);
                    $i = 0;
                    foreach ($urlRuleArr as $key => $item){
                        if (!isset($urlArr[1]) && $i == 0){
                            $url .= '?' . $key . '=' . $item;
                        }else{
                            $url .= '&' . $key . '=' . $item;
                        }
                        $i++;
                    }
                }
                $model->unique_id = md5($url);
                //清除缓存
                $model->params_cache = null;
                $model->data_cache = null;
                $model->change_cache = null;
                if ($model->save()){
                    $this->redirectParent(['index']);
                }else{
                   Yii::$app->session->setFlash('danger',$model->getFirstStrError(). "<重复添加>");
                    return $this->render('config',['model' => $model]);
                }
            }else{
                return $this->render('config',['model' => $model]);
            }
        }
        $model->url_rule = Json::formatJson($model->url_rule);
        $model->change_rule = Json::formatJson($model->change_rule);
        $model->params_rule = Json::formatJson($model->params_rule);

        return $this->render('config',['model' => $model]);
    }

    /**
     * @param $data
     * @return mixed
     * 处理请求数据
     */
    private function dealRequestData($data){
        if (isset($data['ok'])){
            unset($data['ok']);
        }
        if (isset($data['servertime'])){
            unset($data['servertime']);
        }
        if (isset($data['params'])){
            unset($data['params']);
        }
        return $data;
    }

    public function actionTest()
    {
        $searchModel = new DemoDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('test', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * 创建顶级分组
     */
    public function actionCreateTop(){
        $model = new DemoData();
        if ($model->load(Yii::$app->request->post())){
            $model->pid = 0;
            $model->is_parent = 1;
            if ($model->save()){
                $this->redirectParent(['index']);
            }
        }
        return $this->render('create-top',['model' => $model]);
    }

    /**
     * @param $pid
     * @return string
     * @throws NotFoundHttpException
     * 创建下级
     */
    public function actionCreateLower($pid){
        $model = $this->findModel($pid);
        if (Yii::$app->request->isPost){
            $lowerModel = new DemoData();
            $lowerModel->load(Yii::$app->request->post());
            $lowerModel->pid = $pid;
            $lowerModel->is_parent = 1;
            if ($lowerModel->save()){
                $this->redirectParent(['index']);
            }

        }
        return $this->render('create-lower',['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * 查看字段说明
     */
    public function actionDoc($id){
        $model = $this->findModel($id);
        $data = Json::decode($model->data_rule);
        RulesServer::decodeRulesDoc($data);
        $model->doc = Json::encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $globParams = Json::decode($model->global_params);
        if ($globParams){
            RulesServer::decodeRulesDoc($globParams);
        }
        $model->global_params = $globParams == null ? '无' : Json::encode($globParams,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        return $this->render('doc',['model' => $model]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * 改变状态
     */
    public function actionStatus($id){
        $model = $this->findModel($id);
        $model->is_open = $model->is_open == 1 ? 0 : 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string
     * 写文档
     */
    public function actionDocument($id){
        $model = new DocumentForm();
        $model->savePath = '/data/yii/websites/dsj_data/backend/runtime/documents/';
        $model->id = $id;
        $model->content = $model->getContent();
        if ($model->load(Yii::$app->request->post())){
            if ($model->save()){
                Yii::$app->session->setFlash('success','保存成功');
                return $this->render('document',['model' => $model]);
            };
        }
        return $this->render('document',['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * 查看文档
     */
    public function actionShowDocument($id){
        $model = new DocumentForm();
        $model->savePath = 'C:\phpstudy_pro\WWW\sync\project\backend\runtime\documents\\';
        $model->id = $id;
        $model->content = $model->getContent();
        return $this->render('show-document',['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * 替换域名
     */
    public function actionBatchReplace($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){
            $firstChild = DemoData::find()->where(['pid' => $id])->all();
            $list = [];
            $this->getAllNeedReplace($firstChild,$list);
            $errors = [];
            foreach ($list as $item){
                if (!$item->url){
                    continue;
                }
                $res = preg_replace('/(http):\/\/([^\/]+)/i', 'http://'.$model->domain, $item->url);
                $item->url = $res;
                if ($item->save()){
                   continue;
                }else{
                    $errors[] = $item->getErrors();
                }
            }
            if (!$errors){
                $this->redirectParent(['index']);
            }
            Yii::$app->session->setFlash('danger',Json::encode($errors));
        }

        return $this->render('batch-replace',['model' => $model]);
    }

    private function getAllNeedReplace($firstChild,&$res){
        foreach ($firstChild as $item){
            $childs = DemoData::find()->where(['pid' => $item->id])->all();
            if ($childs){
                $res[] = $item;
                $this->getAllNeedReplace($childs,$res);
            }else{
                $res[] = $item;
            }
        }
    }
}

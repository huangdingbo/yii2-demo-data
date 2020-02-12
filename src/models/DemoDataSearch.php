<?php

namespace dsj\demoData\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DemoDataSearch represents the model behind the search form of `backend\models\DemoData`.
 */
class DemoDataSearch extends DemoData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'create_at', 'update_at','is_parent'], 'integer'],
            [['unique_id', 'url', 'url_rule', 'type', 'params_cache', 'params_rule', 'data_cache', 'data_rule', 'change_cache', 'change_rule', 'instruction', 'is_open', 'is_ignore_params','doc','global_params'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DemoData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100], //设置分页条数
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'url_rule', $this->url_rule])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'params_cache', $this->params_cache])
            ->andFilterWhere(['like', 'params_rule', $this->params_rule])
            ->andFilterWhere(['like', 'data_cache', $this->data_cache])
            ->andFilterWhere(['like', 'data_rule', $this->data_rule])
            ->andFilterWhere(['like', 'change_cache', $this->change_cache])
            ->andFilterWhere(['like', 'change_rule', $this->change_rule])
            ->andFilterWhere(['like', 'instruction', $this->instruction])
            ->andFilterWhere(['like', 'is_open', $this->is_open])
            ->andFilterWhere(['like', 'is_ignore_params', $this->is_ignore_params]);

        return $dataProvider;
    }
}
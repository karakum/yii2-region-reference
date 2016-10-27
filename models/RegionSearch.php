<?php

namespace karakum\region\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use karakum\region\models\Region;
use yii\helpers\Html;

/**
 * RegionSearch represents the model behind the search form about `karakum\region\models\Region`.
 */
class RegionSearch extends Region
{

    public $only_root;
    public $search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'country_id', 'level_id', 'status'], 'integer'],
            [['name', 'fullname', 'code', 'created', 'updated', 'deleted'], 'safe'],
            ['only_root', 'boolean'],
            ['search', 'string'],
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
        $query = Region::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (is_null($this->only_root)) {
            $query->andFilterWhere([
                'parent_id' => $this->parent_id,
            ]);
        } elseif ($this->only_root) {
            $query->andWhere([
                'parent_id' => null,
            ]);
        } else {
            $query->andWhere([
                'not', ['parent_id' => null],
            ]);
        }
        if (empty($this->status)) {
            $query->andWhere([
                'not', ['status' => Region::STATUS_DELETED],
            ]);
        } else {
            $query->andWhere([
                'status' => $this->status,
            ]);
        }
        if ($this->search) {
            $query->andWhere([
                'or',
                ['like', 'name', $this->search],
                ['like', 'fullname', $this->search],
            ]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'level_id' => $this->level_id,
            'country_id' => $this->country_id,
            'created' => $this->created,
            'updated' => $this->updated,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }

    public function filterParams()
    {
        $filter = [];
        foreach ($this->safeAttributes() as $key) {
            $value = $this->$key;
            if (!empty($value) || $value == "0") {
                $filter[Html::getInputName($this, $key)] = $value;
            }
        }
        $pageSize = Yii::$app->request->post('per-page');
        if ($pageSize) {
            $filter['per-page'] = $pageSize;
        }
        $sort = Yii::$app->request->post('sort');
        if ($sort) {
            $filter['sort'] = $sort;
        }
        return $filter;
    }

}

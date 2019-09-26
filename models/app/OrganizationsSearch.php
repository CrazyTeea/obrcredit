<?php

namespace app\models\app;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\Organizations;

/**
 * OrganizationsSearch represents the model behind the search form of `app\models\app\Organizations`.
 */
class OrganizationsSearch extends Organizations
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'short_name', 'full_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Organizations::find()->where(['system_status'=>1]);//->select(['students.id','count(students.id)','short_name','organizations.name','full_name'])->joinWith(['students']);

        $query->joinWith(['students'=>function($subquery){
            $subquery->onCondition(['students.status'=>1]);
        }]);
        $query->select(['organizations.*','COUNT(students.id) AS studentsCOUNT']);
        $query->groupBy(['organizations.id']);
        $query->orderBy(['studentsCOUNT'=>SORT_DESC]);

        // add conditions tha t should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>50
            ]
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'full_name', $this->full_name]);

        return $dataProvider;
    }
}

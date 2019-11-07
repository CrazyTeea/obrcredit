<?php

namespace app\models\app;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app\Organizations;
use yii\helpers\ArrayHelper;

/**
 * OrganizationsSearch represents the model behind the search form of `app\models\app\Organizations`.
 */
class OrganizationsSearch extends Organizations
{
    public $isColored;
    public $id_bank;
    public $month;
    public $nPP;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','id_bank','month','nPP'], 'integer'],
            [['isColored'],'integer'],
            [['name', 'short_name', 'full_name'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),['isColored'=>'Пометить']); // TODO: Change the autogenerated stub
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
        $query = Organizations::find()->joinWith(['students as st'])->where(['system_status'=>1]);//->select(['students.id','count(students.id)','short_name','organizations.name','full_name'])->joinWith(['students']);



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


        if ($this->isColored) {
            $query->joinWith(['students' => function ($subquery) {
                $subquery->onCondition(['students.id_bank'=>Yii::$app->session['id_bank'],
                    'students.status'=>2,
                    'MONTH(students.date_start)'=>Yii::$app->session['month'],
                    'students.id_number_pp'=>Yii::$app->session['nPP']]);
            }]);
            $query->select(['organizations.*', 'COUNT(students.id) AS studentsCOUNT']);
            $query->groupBy(['organizations.id']);
            $query->orderBy(['studentsCOUNT' => SORT_DESC]);
        }
        else{
            $query->andFilterWhere(['st.id_bank'=>$this->id_bank,]);
            $query->andFilterWhere(['st.id_number_pp'=>$this->nPP,]);
            $query->andFilterWhere(['MONTH(st.date_start)'=>$this->month]);
        }
        // grid filtering conditions
        $query->andFilterWhere(['id' => $this->id,]);


        $query->andFilterWhere(['like', 'organizations.name', $this->name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'full_name', $this->full_name]);
        $query->groupBy(['id']);

        return $dataProvider;
    }
}

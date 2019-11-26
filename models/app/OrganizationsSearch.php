<?php

namespace app\models\app;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * OrganizationsSearch represents the model behind the search form of `app\models\app\Organizations`.
 */
class OrganizationsSearch extends Organizations
{
    public $isColored;
    public $id_bank;
    public $month;
    public $year;
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

        $query = Organizations::find()->where(['organizations.system_status'=>1]);
        if ($this->isColored){
            $query->select(['organizations.*', 's.status as student_status'])->joinWith(['students s'=>function($q){
                 $q->andWhere([
                    's.id_bank'=>Yii::$app->session['id_bank'],
                    'MONTH(s.date_start)'=>Yii::$app->session['month'],
                     'YEAR(s.date_start)'=>Yii::$app->session['year'],

                    's.id_number_pp'=>Yii::$app->session['nPP']]);
            }]);
            $query->orderBy(['student_status'=>SORT_ASC]);
        }
        else{
            $query->joinWith(['students s'])
                ->andWhere(['s.id_bank'=>$this->id_bank,'s.id_number_pp'=>$this->nPP,'MONTH(s.date_start)'=>$this->month,'YEAR(s.date_start)'=>$this->year]);

        }


        // add conditions tha t should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>25
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {

            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }




       //grid filtering conditions
        $query->andFilterWhere(['id' => $this->id,]);


        $query->andFilterWhere(['like', 'organizations.name', $this->name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'full_name', $this->full_name]);
        $query->groupBy(['id']);

        return $dataProvider;
    }
}

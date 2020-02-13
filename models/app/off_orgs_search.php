<?php


namespace app\models\app;


use yii\data\ActiveDataProvider;

class off_orgs_search extends Organizations
{
    public function search($params){
        $query = self::find()->where(['system_status'=>1]);

        $dataProvider = new ActiveDataProvider(['query'=>$query]);

        $this->load($params);
        if (!$this->validate())
            return $dataProvider;
        $query->andFilterWhere(['id'=>$this->id]);
        $query->andFilterWhere(['like','name',$this->name]);
        $query->andFilterWhere(['like','full_name',$this->full_name]);
        $query->andFilterWhere(['like','short_name',$this->short_name]);
        return $dataProvider;
    }
}
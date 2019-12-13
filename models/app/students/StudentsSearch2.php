<?php


namespace app\models\app\students;


use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class StudentsSearch2 extends Students
{
    public function rules()
    {
        return [
            [['name','code'],'string'],
            [['date_start'],'safe'],
            [['id_org','id_number_pp','id_bank','system_status'],'each','rule'=>['integer']]
        ];
    }
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),['id_org'=>'Организация']); // TODO: Change the autogenerated stub
    }

    public function search($queryParams){
        $students = Students::find()->joinWith(['organization','bank','numberPP']);
        $provider = new ActiveDataProvider(['query'=>$students]);

        $this->load($queryParams);

        if (!$this->validate()){

            return $provider;
        }

        $students->andFilterWhere([
            'id_org'=>$this->id_org,
            'id_number_pp'=>$this->id_number_pp,
            'id_bank'=>$this->id_bank,
            'code'=>$this->code,
            'date_start'=>$this->date_start,
            'system_status'=>$this->system_status
        ]);
        $students->andFilterWhere(['like','students.name',$this->name]);

        return $provider;
    }
}
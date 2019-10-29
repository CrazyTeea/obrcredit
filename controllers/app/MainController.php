<?php


namespace app\controllers\app;


use app\models\app\Banks;
use app\models\app\students\Students;
use Yii;

class MainController extends AppController
{
    public function actionIndex(){
        $studentsByYear = null;
        for ($i = 2018;$i<=2021;$i++){
            if (!($this->cans[0] || $this->cans[1])) {
                $studentsByYear[$i]['studentsCount']=Students::find()->where(['YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->count();
                $studentsByYear[$i]['studentsApprovedCount'] = Students::find()->where(['status'=>2,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->count();
                $studentsByYear[$i]['studentsUnapprovedCount'] = Students::find()->where(['status'=>1,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->count();
            }else {
                $studentsByYear[ $i ][ 'studentsCount' ] = Students::find()->where( ['YEAR(date_start)' => $i] )->count();
                $studentsByYear[ $i ][ 'studentsApprovedCount' ] = Students::find()->where( ['status' => 2, 'YEAR(date_start)' => $i] )->count();
                $studentsByYear[ $i ][ 'studentsUnapprovedCount' ] = Students::find()->where( ['status' => 1, 'YEAR(date_start)' => $i] )->count();
            }
        }
        return $this->render('index',compact('studentsByYear'));
    }
    public function actionMonth($year){
        Yii::$app->session['year']=$year;
        for ($i = 1;$i<=12;$i++){
            if (!($this->cans[0] || $this->cans[1])) {
                $s197 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 1, 'id_org'=>Yii::$app->session['id_org']] )->select( ['id_bank'] );
                $s699 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 2, 'id_org'=>Yii::$app->session['id_org']] )->select( ['id_bank'] );
                $s1026 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 3, 'id_org'=>Yii::$app->session['id_org']] )->select( ['id_bank'] );
            }
            else{
                $s197 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 1] )->select( ['id_bank'] );
                $s699 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 2] )->select( ['id_bank'] );
                $s1026 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 3] )->select( ['id_bank'] );
            }
            $studentsByMonth[$i][197]['students'] = $s197->count();
            $studentsByMonth[$i][699]['students'] = $s699->count();
            $studentsByMonth[$i][1026]['students'] = $s1026->count();
            $studentsByMonth[$i][197]['bank'] =  $s197->groupBy('id_bank')->column();
            $studentsByMonth[$i][699]['bank'] =  $s699->groupBy('id_bank')->column();
            $studentsByMonth[$i][1026]['bank'] = $s1026->groupBy('id_bank')->column();
        }
        $banks = Banks::find()->select('name')->column();

        return $this->render('month',compact('studentsByMonth','banks'));
    }
}
<?php


namespace app\controllers\app;


use app\models\app\Banks;
use app\models\app\Oplata;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;
use app\models\app\students\StudentsHistory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class MainController extends AppController
{
    public function actionIndex(){
        $this->updateRouteHistory('/app/main/index');
        $studentsByYear = null;
        for ($i = 2018;$i<=2021;$i++){
            if (!($this->cans[0] || $this->cans[1])) {
                $studentsByYear[$i]['studentsCount']=Students::find()->where(['system_status'=>1,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[$i]['studentsApprovedCount'] = Students::find()->where(['system_status'=>1,'status'=>2,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[$i]['studentsUnapprovedCount'] = Students::find()->where(['system_status'=>1,'status'=>1,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->select(['name','status'])->distinct(['name'])->count();
            }else {
                $studentsByYear[ $i ][ 'studentsCount' ] = Students::find()->where( ['system_status'=>1,'YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[ $i ][ 'studentsApprovedCount' ] = Students::find()->where( ['system_status'=>1,'status' => 2, 'YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[ $i ][ 'studentsUnapprovedCount' ] = Students::find()->where( ['system_status'=>1,'status' => 1, 'YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
            }
        }
        return $this->render('index',compact('studentsByYear'));
    }
    public function actionMonth($year = null){
        $this->updateRouteHistory('/app/main/month');
        if (is_null($year))
            return $this->redirect(['index']);

        Yii::$app->session['year']=$year;
        $id_org = Yii::$app->session->get('id_org');

        $orgSelect = "";

        if ($this->cans[2]) {
            $orgSelect = "and id_org=$id_org";
        }

        $studentsByMonth = Students::find()
            ->select(['YEAR(date_start) year','MONTH(date_start) month','MIN(status) status', 'numbers_pp.id id_number_pp','banks.id id_bank','banks.name bank_name', 'COUNT(t1.id) count'])->from(['t1'=>
                "(SELECT id,status,date_start,id_number_pp,id_bank FROM `students` WHERE system_status=1 and status != 0 $orgSelect )"])->joinWith(['numberPP','bank'])
            ->where(['year(date_start)'=>$year])
            ->groupBy(['year' , 'month' , 'id_number_pp', 'id_bank'])
            ->orderBy(['year'=>SORT_ASC , 'month' =>SORT_ASC, 'id_number_pp'=>SORT_ASC])->all();
        $export = [];

        $h_e_query = StudentsHistory::find()->joinWith(['student']);
        $export = [
            'h_e_provider'=>new ActiveDataProvider(['query'=>$h_e_query,'pagination'=>false]),
        ];

        $payments_model = Oplata::find()->select(['*','YEAR(payment_date) payment_year','MONTH(payment_date) payment_month'])->where(['YEAR(payment_date)'=>$year])->all();
        $payments = null;
        $payments_status = null;
        $nums = NumbersPp::find()->all();
        $banks = Banks::find()->all();


        for ($i=1;$i<=12;$i++){
            foreach ($nums as $num){
                foreach ($banks as $bank){
                    if ($payments_model) {
                        foreach ($payments_model as $pay_model) {
                            if ( !strcasecmp($pay_model->payment_year , $year) and
                            !strcasecmp($pay_model->payment_month , $i) and
                            $pay_model->numberpp_id == $num->id and
                            $pay_model->bank_id == $bank->id) {
                                $payments_status[$i][$num->id][$bank->id]= true;
                                $payments[$i][$num->id][$bank->id] =  $pay_model;
                            } elseif(!isset($payments[$i][$num->id][$bank->id])) {
                                $payments[$i][$num->id][$bank->id] = new Oplata();
                                $payments_status[$i][$num->id][$bank->id]= false;
                            }
                        }
                    }else {
                        $payments_status[$i][$num->id][$bank->id]= false;
                        $payments[$i][$num->id][$bank->id] = new Oplata();}

                }
            }
            $export['e_providers'][$i] = ($this->cans[2]) ?
                new ActiveDataProvider([ 'query'=>Students::find()->where(['system_status'=>1,'MONTH(date_start)'=>$i,'YEAR(date_start)'=>$year,'id_org'=>$id_org])])
                :
                new ActiveDataProvider([ 'query'=>Students::find()->where(['system_status'=>1,'MONTH(date_start)'=>$i,'YEAR(date_start)'=>$year])]);
        }

        $st_history_subq = StudentsHistory::find()->select(['id_student','k.id','k.id_number_pp','k.date_start'])->where(['year(k.date_start)'=>$year])
            ->joinWith(['student k']);
        $nums = (new Query())->select(['npp.id','number','sh.*','COUNT(id_number_pp) as students_count'])
            ->from(['numbers_pp npp'])->join('JOIN',['sh'=>$st_history_subq],'sh.id_number_pp = npp.id')->groupBy(['npp.id'])->all();


        return $this->render('month',compact('studentsByMonth','export','nums','payments','payments_status'));
    }
}
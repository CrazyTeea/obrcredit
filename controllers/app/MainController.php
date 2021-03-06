<?php


namespace app\controllers\app;


use app\models\app\Banks;
use app\models\app\Oplata;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;
use app\models\app\students\StudentsHistory;
use Generator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class MainController extends AppController
{
    public function actionIndex(){
        $this->updateRouteHistory('/app/main/index');
        $studentsByYear = null;
        return $this->render('index');
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

        $studentsByMonth = Yii::$app->db->createCommand(
            "SELECT 
    year, month, status, id_number_pp, id_bank, COUNT(id) count, bank_name,system_status
FROM
    (SELECT 
         students.id,
            YEAR(date_start) year,
            MONTH(date_start) month,
            status,
            id_number_pp,
            id_bank,
            b.name bank_name,
            students.system_status
    FROM
        students
        join banks b on b.id = id_bank
    WHERE
    students.system_status in (1,0)
    
        $orgSelect) t
GROUP BY year , month , status , id_number_pp , id_bank;"
        )->queryAll();




        $export = [];

        $payments_model = Oplata::find()->select(['*','YEAR(payment_date) payment_year','MONTH(payment_date) payment_month'])->where(['YEAR(payment_date)'=>$year])->all();
        $payments = null;
        $payments_status = null;
        $nums = NumbersPp::find()->all();
        $banks = Banks::find()->all();

        $janStudents['otch'] = Students::find()->where(['system_status'=>1,'YEAR(date_start)'=>$year-1,'MONTH(date_start)'=>12,'id_number_pp'=>1])->andWhere(['<>','osnovanie',0])->groupBy(['date_credit'])->count();
        $janStudents['vip'] = Students::find()->where(['system_status'=>1,'YEAR(date_start)'=>$year-1,'MONTH(date_start)'=>12,'id_number_pp'=>1,])->andWhere(['isEnder'=>1])->groupBy(['date_credit'])->count();


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
                new ActiveDataProvider([ 'query'=>Students::find()->where( [
                    'system_status'=>1,
                    'MONTH(date_start)'=>$i,
                    'YEAR(date_start)'=>$year,
                    'id_org'=>$id_org,
                ] )->orWhere(['id'=>Students::find()->select(['students.id'])->join('join','students_history','students_history.id_student=students.id')->where([
                    'MONTH(date_start)'=>$i,
                    'YEAR(date_start)'=>$year,
                    'id_org'=>$id_org,
                ])->asArray()])])

                :
                new ActiveDataProvider([ 'query'=>Students::find()->where( [
                    'system_status'=>1,
                    'MONTH(date_start)'=>$i,
                    'YEAR(date_start)'=>$year,
                ] )->orWhere(['id'=>Students::find()->select(['students.id'])->join('join','students_history','students_history.id_student=students.id')->where([
                    'MONTH(date_start)'=>$i,
                    'YEAR(date_start)'=>$year,
                ])->asArray()])]);
        }

        $st_history_subq = StudentsHistory::find()->select(['id_student','k.id','k.id_number_pp','k.date_start'])->where(['year(k.date_start)'=>$year])
            ->joinWith(['student k']);
        $nums = (new Query())->select(['npp.id','number','sh.*','COUNT(id_number_pp) as students_count'])
            ->from(['numbers_pp npp'])->join('JOIN',['sh'=>$st_history_subq],'sh.id_number_pp = npp.id')->groupBy(['npp.id'])->all();


        return $this->render('month',compact('studentsByMonth','export','nums','payments','payments_status','janStudents'));
    }


    /**
     * @param $arr
     * @param $month
     * @param $bank
     * @param $num
     * @param bool $custom_val
     * @param int $val
     * @return int
     */
    private function sortSt($arr, $month, $bank, $num, $custom_val = false, $val = 0){
        $cnt=0;
        $date_credit = null;
        if (!$custom_val ) {
            foreach ($arr as $item) {
                if (date('m', strtotime($item->date_start)) == $month and
                    $item->id_bank == $bank and
                    $item->id_number_pp == $num and
                    $date_credit != $item->date_credit
                ) {
                    $cnt++;
                    $date_credit = $item->date_credit;
                }
            }
        }
        else {
            foreach ($arr as $item) {
                if (date('m', strtotime($item->date_start)) == $month and
                    $item->id_bank == $bank and
                    $item->id_number_pp == $num and
                    $item->{$custom_val} == $val and
                    $date_credit != $item->date_credit
                ) {
                    $cnt++;
                    $date_credit = $item->date_credit;
                }
            }
        }
        return $cnt;
    }
    private function gen(Generator $generator){
        $count = 0;
        foreach($generator as $value)
        {
            $count++;
        }
        return $count;
    }
    public function actionExport($year){

        $student=[];
        $banks = Banks::find()->all();
        $nums = NumbersPp::find()->all();
        $student = Students::find()->where(['system_status'=>1,'YEAR(date_start)'=>$year])->all();




        if (Yii::$app->request->post()){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($this->renderPartial('export',compact('year','student','banks','nums')));

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('uploads/write.xls');

            Yii::$app->response->sendFile('uploads/write.xls')->send();
            unlink('uploads/write.xls');
        }




        return $this->render('export',compact('year','student','banks','nums'));
    }
}
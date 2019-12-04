<?php


namespace app\controllers\app;


use app\models\app\Banks;
use app\models\app\students\Students;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class MainController extends AppController
{
    public function actionIndex(){
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
        if (is_null($year))
            return $this->redirect(['index']);

        Yii::$app->session['year']=$year;
        $id_org = Yii::$app->session->get('id_org');

        $orgSelect = "";
        $exportColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','label'=>"ФИО обучающегося"],
            ['attribute'=>'organization','value'=>'organization.short_name','label'=>'Наименование ООВО'],
            ['attribute'=>'code','label'=>'Код направления подготовки'],
            ['attribute'=>'education_status','label'=>'Статус обучающегося','content'=>function($model){
                $os = mb_substr(Students::getOsnovanie()[ !empty($model->osnovanie) ? $model->osnovanie : 0  ],0,50);
                $data = "";
                switch ($model->osnovanie){
                    case 1:
                    case 2:
                    case 3:{
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:{
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:{
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:{$data = ""; break;}
                }
                $date = null;
                if (isset($model->dateLastStatus) and isset($model->dateLastStatus->date_end))
                    $date = Yii::$app->getFormatter()->asDate($model->dateLastStatus->date_end);

                $dta = ($date) ? "$date $data" : '';

                return $model->education_status ? $model->perevod ? 'Переведен на бюджет': "Обучается" : $dta;
            }],
            ['attribute'=>'grace_period','value'=>
                function($model){
                    $data = "";
                    switch ($model->grace_period){
                        case 1:{
                            $date = ($model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period1).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period1) : '';
                            $data = Students::getGracePeriod()[1] . "($date)";
                            break;
                        }
                        case 2:{
                            $date = ($model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period2).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period2) : '';
                            $data = Students::getGracePeriod()[2] . "($date)";
                            break;
                        }
                        case 3:{
                            $date = ($model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate($model->date_start_grace_period3).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period3) : '';
                            $data = Students::getGracePeriod()[3] . "($date)";
                            break;
                        }
                        default: {$data = ''; break;}
                    }
                    return $data;
                }
                ,'label'=>'Пролонгация льготного периода'
            ],
            ['attribute'=>'date_credit','label'=>'Дата заключения кредитного договора',],
            ['attribute'=>'dateLastStatus','value'=>'dateLastStatus.updated_at','label'=>'Дата изменения данных'],
        ];
        if (!$this->cans[2]) {
            $exportColumns = ArrayHelper::merge( $exportColumns, [
                ['attribute' => 'numberPP','value' => 'numberPP.number', 'label' => 'Номер ПП по образовательному кредиту'],
                ['attribute' => 'bank','value'=>'bank.name', 'label' => 'Наименование банка или иной кредитной организации'],
                ['attribute' => 'date_status', 'format' => 'date', 'label' => 'Дата утверждения отчета'],
            ] );
        }
        else{
            $orgSelect = "and id_org=$id_org";
        }

        $studentsByMonth = Students::find()
            ->select(['YEAR(date_start) year','MONTH(date_start) month','MIN(status) status', 'numbers_pp.id id_number_pp','banks.id id_bank','banks.name bank_name', 'COUNT(t1.id) count'])->from(['t1'=>
                "(SELECT id,status,date_start,id_number_pp,id_bank FROM `students` WHERE system_status=1 and status != 0 $orgSelect )"])->joinWith(['numberPP','bank'])
            ->groupBy(['year' , 'month' , 'id_number_pp', 'id_bank'])
            ->orderBy(['year'=>SORT_ASC , 'month' =>SORT_ASC, 'id_number_pp'=>SORT_ASC])->all();
        $exportQuery = [];
        for ($i=1;$i<=12;$i++){
            $exportQuery[$i] = ($this->cans[2]) ?
                new ActiveDataProvider([ 'query'=>Students::find()->where(['system_status'=>1,'MONTH(date_start)'=>$i,'YEAR(date_start)'=>$year,'id_org'=>$id_org])])
                :
                new ActiveDataProvider([ 'query'=>Students::find()->where(['system_status'=>1,'MONTH(date_start)'=>$i,'YEAR(date_start)'=>$year])]);
        }


        return $this->render('month',compact('studentsByMonth','exportQuery','exportColumns'));
    }
}
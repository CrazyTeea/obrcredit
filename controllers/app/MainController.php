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
                $studentsByYear[$i]['studentsCount']=Students::find()->where(['YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->distinct(['name'=>true])->count();
                $studentsByYear[$i]['studentsApprovedCount'] = Students::find()->where(['status'=>2,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->distinct(['name'=>true])->count();
                $studentsByYear[$i]['studentsUnapprovedCount'] = Students::find()->where(['status'=>1,'YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']])->distinct(['name'=>true])->count();
            }else {
                $studentsByYear[ $i ][ 'studentsCount' ] = Students::find()->where( ['YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[ $i ][ 'studentsApprovedCount' ] = Students::find()->where( ['status' => 2, 'YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
                $studentsByYear[ $i ][ 'studentsUnapprovedCount' ] = Students::find()->where( ['status' => 1, 'YEAR(date_start)' => $i] )->select(['name','status'])->distinct(['name'])->count();
            }
        }
        return $this->render('index',compact('studentsByYear'));
    }
    public function actionMonth($year = null){
        if (is_null($year))
            return $this->redirect(['index']);

        Yii::$app->session['year']=$year;


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
        for ($i = 1;$i<=12;$i++){
            if (!($this->cans[0] || $this->cans[1])) {
                $s197 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 1, 'id_org'=>Yii::$app->session['id_org']] )->select(['id_bank'] );
                $s699 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 3, 'id_org'=>Yii::$app->session['id_org']] )->select( ['id_bank'] );
                $s1026 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 2, 'id_org'=>Yii::$app->session['id_org']] )->select( ['id_bank'] );
            }
            else{
                $s197 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 1] )->select( ['id_bank'] );
                $s699 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 3] )->select( ['id_bank'] );
                $s1026 = Students::find()->where( ['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i, 'id_number_pp' => 2] )->select( ['id_bank'] );
            }
            $studentsByMonth[$i]['exportPr'] = (!$this->cans[2]) ?
                new ActiveDataProvider(['query'=>Students::find()->where(['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i]),'pagination'=>['pageSize'=>10]])
                : new ActiveDataProvider(['query'=>Students::find()->where(['YEAR(date_start)' => $year, 'MONTH(date_start)' => $i,'id_org'=>Yii::$app->session['id_org']]),'pagination'=>['pageSize'=>10]]);

            $wBank197 = clone $s197;
            $wBank699 = clone $s699;
            $wBank1026 =clone $s1026;

            $snA197 = clone $s197;
            $snA699 = clone $s699;
            $snA1026 = clone $s1026;


            $studentsByMonth[$i][197]['students']['count'] = $s197->groupBy(['name'])->count();
            $studentsByMonth[$i][699]['students']['count'] = $s699->groupBy(['name'])->count();
            $studentsByMonth[$i][1026]['students']['count'] = $s1026->groupBy(['name'])->count();


            $studentsByMonth[$i][197]['bank'] = $wBank197->groupBy(['id_bank'])->column() ;
            $studentsByMonth[$i][699]['bank'] =  $wBank699->groupBy(['id_bank'])->column();
            $studentsByMonth[$i][1026]['bank'] = $wBank1026->groupBy(['id_bank'])->column();

            $studentsByMonth[$i][197]['students']['notApproved'] = $snA197->andWhere(['status'=>1])->count();
            $studentsByMonth[$i][699]['students']['notApproved'] = $snA699->andWhere(['status'=>1])->count();
            $studentsByMonth[$i][1026]['students']['notApproved'] = $snA1026->andWhere(['status'=>1])->count();




        }
        $banks = Banks::find()->select('name')->column();

        return $this->render('month',compact('studentsByMonth','banks','exportColumns'));
    }
}
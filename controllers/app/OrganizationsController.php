<?php

namespace app\controllers\app;

use app\models\app\students\Students;

use Yii;
use app\models\app\Organizations;
use app\models\app\OrganizationsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganizationsController implements the CRUD actions for Organizations model.
 */
class OrganizationsController extends AppController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction( $action)
    {

        $this->cans = Yii::$app->session['cans'];
        return parent::beforeAction($action);
    }

    /**
     * Lists all Organizations models.
     * @return mixed
     */
    public function actionIndex()
    {


        $searchModel = new OrganizationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $modelColored = Organizations::find();

        $modelColored->joinWith(['students' => function ($subquery) {
            $subquery->onCondition(['students.status' => 2]);
        }]);
        $modelColored->select(['organizations.*', 'COUNT(students.id) AS studentsCOUNT']);
        $modelColored->groupBy(['organizations.id']);
        $modelColored->orderBy(['studentsCOUNT' => SORT_DESC]);

        $dataProviderColored  = new ActiveDataProvider(['query'=>$modelColored,'pagination'=>false]);

        $studentsExport = Students::find();
        $exportProvider = new ActiveDataProvider(['query'=>$studentsExport,'pagination'=>false]);

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

                return $model->education_status ? "Обучается" : $dta;
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



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderStudent'=>$exportProvider,
            'exportColumns'=>$exportColumns,
            'clrPr'=>$dataProviderColored,
            'clrMd'=>$modelColored
        ]);
    }


    public function actionByBank($id_bank,$month,$nPP){

        Yii::$app->session['bank'] = $id_bank;

        $searchModel = new OrganizationsSearch();
        $searchModel->month = $month;
        Yii::$app->session['month'] = $month;
        $searchModel->id_bank = $id_bank;
        Yii::$app->session['id_bank'] = $id_bank;
        Yii::$app->session['nPP'] = $nPP;
        $searchModel->nPP=$nPP;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $modelColored = Organizations::find();

        $modelColored->joinWith(['students as st'])->andWhere(['st.id_bank'=>$id_bank,'st.status'=>1,'MONTH(st.date_start)'=>$month,'st.id_number_pp'=>$nPP]);

        $modelColored->groupBy(['organizations.id']);
        //$modelColored->orderBy(['studentsCOUNT' => SORT_DESC]);

        $dataProviderColored  = new ActiveDataProvider(['query'=>$modelColored,'pagination'=>false]);

        $studentsExport = Students::find()->where(['id_bank'=>$id_bank]);
        $exportProvider = new ActiveDataProvider(['query'=>$studentsExport,'pagination'=>false]);

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

                return $model->education_status ? "Обучается" : $dta;
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


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderStudent'=>$exportProvider,
            'exportColumns'=>$exportColumns,
            'clrPr'=>$dataProviderColored,
            'clrMd'=>$modelColored
        ]);
    }

    /**
     * Displays a single Organizations model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Organizations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Organizations();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Organizations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Organizations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Organizations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organizations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organizations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

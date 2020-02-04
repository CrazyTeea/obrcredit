<?php

namespace app\controllers\app;

use app\models\app\students\Students;

use Throwable;
use Yii;
use app\models\app\Organizations;
use app\models\app\OrganizationsSearch;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

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
     * @throws BadRequestHttpException
     */
    public function beforeAction( $action)
    {

        $this->cans = Yii::$app->session['cans'];
        return parent::beforeAction($action);
    }


    public function actionUsers(){

        $searchModel = new OrganizationsSearch();
        $dataProvider = $searchModel->searchUsers(Yii::$app->getRequest()->getQueryParams());


        return $this->render('users',compact('searchModel','dataProvider'));
    }

    /**
     * Lists all Organizations models.
     * @return mixed
     */
    public function actionIndex()
    {

        $this->updateRouteHistory('/app/organizations/index');
        $searchModel = new OrganizationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $modelColored = Organizations::find();

        $modelColored->joinWith(['students' => function ($subquery) {
            $subquery->onCondition(['students.status' => 2,'students.system_status'=>1,]);
        }]);
        $modelColored->select(['organizations.*', 'COUNT(students.id) AS studentsCOUNT']);
        $modelColored->groupBy(['organizations.id']);
        $modelColored->orderBy(['studentsCOUNT' => SORT_DESC]);

        $dataProviderColored  = new ActiveDataProvider(['query'=>$modelColored,'pagination'=>false]);

        $studentsExport = Students::find();
        $exportProvider = new ActiveDataProvider(['query'=>$studentsExport,'pagination'=>false]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderStudent'=>$exportProvider,
            'clrPr'=>$dataProviderColored,
            'clrMd'=>$modelColored
        ]);
    }


    /**
     * @param null $id_bank
     * @param null $month
     * @param null $nPP
     * @return string|Response
     */
    public function actionByBank( $id_bank=null, $month = null, $nPP=null){

        $this->updateRouteHistory('/app/organizations/by-bank');
        if (is_null($id_bank) || is_null($month) || is_null($nPP))
            return $this->redirect(['app/main/index']);
        if (!Yii::$app->session->has('year'))
            return $this->redirect(['app/main/index']);

        $searchModel = new OrganizationsSearch();
        $searchModel->month = $month;
        $searchModel->year = Yii::$app->session->get('year');
        Yii::$app->session['month'] = $month;
        $searchModel->id_bank = $id_bank;
        Yii::$app->session['id_bank'] = $id_bank;
        Yii::$app->session['nPP'] = $nPP;
        $searchModel->nPP=$nPP;
        if (key_exists('OrganizationsSearch',Yii::$app->request->queryParams)){
            if (key_exists('isColored',Yii::$app->request->queryParams['OrganizationsSearch'])){
                $searchModel->isColored = Yii::$app->request->queryParams['OrganizationsSearch']['isColored'];
            }
        }


       // $searchModel->isColored = Yii::$app->request->post([''])

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $modelColored = Organizations::find();

        $modelColored->joinWith(['students st'])->where([
            'st.system_status'=>1,
            'st.id_bank'=>$id_bank,'st.status'=>1,
            'MONTH(st.date_start)'=>$month,
            'YEAR(st.date_start)'=>$searchModel->year,
            'st.id_number_pp'=>$nPP
        ]);

        $modelColored->groupBy(['organizations.id']);
        //$modelColored->orderBy(['studentsCOUNT' => SORT_DESC]);

        $dataProviderColored  = new ActiveDataProvider(['query'=>$modelColored]);

        $studentsExport =  Students::find()->where([
            'system_status'=>1,
            'id_bank'=>$id_bank,
            'MONTH(date_start)'=>$month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$nPP
        ])->with(['bank', 'numberPP', 'dateLastStatus', 'organization']);

        $exportProvider = new ActiveDataProvider(['query'=>$studentsExport]);




        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderStudent'=>$exportProvider,
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
        $this->updateRouteHistory('/app/organizations/view');
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
        $this->updateRouteHistory('/app/organizations/create');
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
        $this->updateRouteHistory('/app/organizations/update');
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
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->updateRouteHistory('/app/organizations/delete');
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

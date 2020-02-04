<?php

namespace app\controllers\app;

use app\models\app\students\Changes;
use app\models\app\students\Students;
use app\models\app\students\StudentsSearch;
use app\models\User;
use Yii;
use app\models\app\students\StudentsHistory;
use app\models\app\students\StudentsHistorySearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentsHistoryController implements the CRUD actions for StudentsHistory model.
 */
class StudentsHistoryController extends AppController
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
     * Lists all StudentsHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->updateRouteHistory('/app/students-history/index');
        $searchModel = new StudentsHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $changes = Changes::findAll(['system_status'=>1]);

        return $this->render('index', compact('searchModel','dataProvider','changes'));
    }
    public function actionGetByNumberAndYear($id_number_pp,$year){
        Yii::$app->session->set('year',$year);
        Yii::$app->session->set('nPP',$id_number_pp);



        $this->updateRouteHistory('/app/students-history/get-by-number-and-year');

        $searchModel = new StudentsHistorySearch();
        $searchModelEnd = new StudentsSearch();
        $searchModelOtch = new StudentsSearch();
        $searchModelOtch->year = $searchModelEnd->year = $year;
        $searchModelOtch->education_status = 0;
        $searchModelOtch->id_number_pp = $searchModelEnd->id_number_pp = $id_number_pp;
        $searchModel->id_number_pp = $id_number_pp;
        $searchModel->year = $year;
        //  var_dump(Yii::$app->request->queryParams);exit();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2 = $searchModelEnd->search(Yii::$app->request->queryParams);
        $dataProvider3 = $searchModelOtch->search(Yii::$app->request->queryParams);

        $changes = ArrayHelper::map(Changes::find()->where(['system_status'=>1])->select(['system_status','id','change'])->all(),'id','change');

        return $this->render('index', compact('searchModel','dataProvider','searchModelEnd','dataProvider2','searchModelOtch','dataProvider3','changes'));
    }
    public function actionAdd(int $id){
        $this->updateRouteHistory('/app/students-history/add');
        $id_org = null;
        if (Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $id_org = $post['id_org'];
        }else {
            $user = User::findIdentity(Yii::$app->user->id);
            $id_org = $user->id_org;
        }
        $model = $this->findModel($id);
        $st = Students::findOne(['id'=>$model->id_student]);
        $allStudents = Students::findAll(['name'=>$st->name,'date_credit'=>$st->date_credit]);

        $model->id_user_to = Yii::$app->user->id;
        foreach ($allStudents as $student){
            $student->system_status = 1;
            $student->id_org_old = $student->id_org;
            $student->id_org = $id_org;
            $student->save(false);
        }
        $model->save(false);
        return $this->redirect(['get-by-number-and-year','id_number_pp'=>$st->id_number_pp,'year'=>Yii::$app->session->get('year')]);
    }

    /**
     * Displays a single StudentsHistory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->updateRouteHistory('/app/students-history/view');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StudentsHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->updateRouteHistory('/app/students-history/create');
        $model = new StudentsHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentsHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->updateRouteHistory('/app/students-history/update');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StudentsHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->updateRouteHistory('/app/students-history/delete');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StudentsHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentsHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentsHistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

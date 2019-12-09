<?php

namespace app\controllers\app;

use app\models\app\students\Changes;
use Yii;
use app\models\app\students\StudentsHistory;
use app\models\app\students\StudentsHistorySearch;
use app\controllers\app\AppController;
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
        $searchModel = new StudentsHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $changes = Changes::findAll(['system_status'=>1]);

        return $this->render('index', compact('searchModel','dataProvider','changes'));
    }
    public function actionGetByNumberAndYear($id_number_pp,$year){
        Yii::$app->session->set('year',$year);
        Yii::$app->session->set('nPP',$id_number_pp);



        $searchModel = new StudentsHistorySearch();
        $searchModel->id_number_pp = $id_number_pp;
        $searchModel->year = $year;
      //  var_dump(Yii::$app->request->queryParams);exit();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



        $changes = ArrayHelper::map(Changes::find()->where(['system_status'=>1])->select(['system_status','id','change'])->all(),'id','change');

        return $this->render('index', compact('searchModel','dataProvider','changes'));
    }

    /**
     * Displays a single StudentsHistory model.
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
     * Creates a new StudentsHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
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

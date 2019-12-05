<?php

namespace app\controllers\app;

use Yii;
use app\models\app\students\StudentsHistory;
use app\models\app\students\StudentsHistorySearch;
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionGetByNumberAndYear($id_number_pp,$year){
        Yii::$app->session->set('year',$year);
        Yii::$app->session->set('nPP',$id_number_pp);

        $searchModel = new StudentsHistorySearch();
        $searchModel->id_number_pp = $id_number_pp;
        $searchModel->year = $year;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionAdd($id){
        $model = $this->findModel($id);
        $model->id_user_to = Yii::$app->getUser()->getId();
        $model->save(false);
        return $this->redirect(['index']);
    }

    /*
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

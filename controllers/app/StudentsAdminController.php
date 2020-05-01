<?php

namespace app\controllers\app;

use app\models\app\students\Students;
use app\models\app\students\StudentsHistory;
use Throwable;
use Yii;
use app\models\StudentsAdmin;
use app\models\StudentsAdminSearch;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentsAdminController implements the CRUD actions for StudentsAdmin model.
 */
class StudentsAdminController extends AppController
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
     * Lists all StudentsAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentsAdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentsAdmin model.
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
     * Creates a new StudentsAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentsAdmin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentsAdmin model.
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
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionAddHistory($id){
        $model = $this->findModel($id);
        $models = StudentsAdmin::find()->where(['name'=>$model->name,'date_credit'=>$model->date_credit])->all();
        foreach ($models as $m){
            $m->system_status = 0;
            $m->save(false);
        }
        $s_histroy = StudentsHistory::findOne(['id_student'=>$id]);
        if (!$s_histroy)
            $s_histroy = new StudentsHistory();
        $s_histroy->id_student= $id;
        $s_histroy->id_user_from = Yii::$app->getUser()->getId();
        $s_histroy->save();
    }

    /**
     * Deletes an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model)
            $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteLogic($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->system_status = 0;
            $model->save(false);
        }
        return $this->redirect(['view','id'=>$id]);
    }
    /**
     * Deletes an existing StudentsHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDeleteZhurnalAll($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $ids = StudentsAdmin::find()
                ->select(['id'])
                ->where(['name' => $model->name])
                ->orWhere(['date_credit' => $model->date_credit])->all();
            StudentsHistory::deleteAll(['id_student' => $ids]);
        }
        return $this->redirect(['view','id'=>$id]);
    }
    /**
     * Recover an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionRecover($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->system_status = 1;
            $model->save(false);
        }
        return $this->redirect(['view','id'=>$id]);
    }
    /**
     * Deletes an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionDeleteLogicAll($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $models = StudentsAdmin::find()
                ->where(['name' => $model->name])
                ->orWhere(['date_credit' => $model->date_credit])
                ->all();
            foreach ($models as $item) {
                $item->system_status = 0;
                $item->save(false);
            }
        }
        return $this->redirect(['view','id'=>$id]);
    }


    /**
     * Recover an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionRecoverAll($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $models = StudentsAdmin::find()
                ->where(['name' => $model->name])
                ->orWhere(['date_credit' => $model->date_credit])
                ->all();
            foreach ($models as $item) {
                $item->system_status = 1;
                $item->save(false);
            }
        }
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * Deletes an existing StudentsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteAll($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $models = StudentsAdmin::find()
                ->where(['name' => $model->name])
                ->orWhere(['date_credit' => $model->date_credit])
                ->all();
            foreach ($models as $item) {
                $item->delete();
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the StudentsAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentsAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentsAdmin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

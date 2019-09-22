<?php

namespace app\controllers\app;

use app\models\app\Organizations;
use app\models\User;
use Yii;
use app\models\app\students\Students;
use app\models\app\students\StudentsSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentsController implements the CRUD actions for Students model.
 */
class StudentsController extends AppController
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
     * Lists all Students models.
     * @param null $id
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new StudentsSearch();
        if (!empty($id))
            Yii::$app->session['id_org'] = $id;
        if (!(User::$cans[0] || User::$cans[1]))
            Yii::$app->session['id_org'] = User::findIdentity(Yii::$app->user->id)->id_org ? User::findIdentity(Yii::$app->user->id)->id_org : 1;
        Yii::$app->session['short_name_org']=Organizations::findOne(Yii::$app->session['id_org'])->short_name;

        $searchModel->id_org = Yii::$app->session['id_org'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Students model.
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
     * Creates a new Students model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Students();
        $orgs = Organizations::getOrgs();

        if ($model->load(Yii::$app->request->post())) {
            $model->status=0;
            $model->date_create = date('Y-m-d');
            $model->date_education_status = date('Y-m-d');
            $model->id_org = Yii::$app->session['id_org'];
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'orgs'=>$orgs,
           // 'id_org'=>Yii::$app->session['id_org']
        ]);
    }

    /**
     * Updates an existing Students model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       // $orgs = ArrayHelper::map(Organizations::find()->select(['id','short_name'])->all(),'id','short_name');
        $orgs = Organizations::getOrgs();

        if ($model->load(Yii::$app->request->post())) {
            if (User::$cans[0] || User::$cans[1])
                $model->status=1;
            $model->date_education_status = date('Y-m-d');
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'orgs'=>$orgs,
           // 'id_org'=>Yii::$app->session['id_org']
        ]);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionKek(){
        $user = User::findOne(['username'=>'user@admin.ru']);
        $user->id_org=100;
        $user->save();
        return $this->redirect(['site/index']);
    }

    /**
     * Finds the Students model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Students the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Students::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

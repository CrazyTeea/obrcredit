<?php

namespace app\controllers\app;


use app\models\app\Organizations;
use app\models\app\students\DatesEducationStatus;
use app\models\app\students\StudentDocs;
use app\models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use app\models\app\students\Students;
use app\models\app\students\StudentsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentsController implements the CRUD actions for Students model.
 */
class StudentsController extends AppController
{
    private $cans;
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
    public function beforeAction($action)
    {

        $this->cans = Yii::$app->session['cans'];
        return parent::beforeAction($action);
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
        if (!($this->cans[0] || $this->cans[1]))
            Yii::$app->session['id_org'] = User::findIdentity(Yii::$app->user->id)->id_org ? User::findIdentity(Yii::$app->user->id)->id_org : 1;
        Yii::$app->session['short_name_org']=Organizations::findOne(Yii::$app->session['id_org'])->name;

        $searchModel->id_org = Yii::$app->session['id_org'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExport($id = null){
        $student = Students::findOne($id);
        $document = new TemplateProcessor('templates/export.docx');
        $document->setValue('fio',$student->name);
        $document->setValue('code',$student->code);
        $document->setValue('e_status',$student->education_status ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie1',$student->osnovanie == 1 ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie2',$student->osnovanie == 2 ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie3',$student->osnovanie == 3 ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie4',$student->osnovanie == 4 ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie5',$student->osnovanie == 5 ? '&#9745;' : '&#9744;');
        $document->setValue('osnovanie6',$student->osnovanie == 6 ? '&#9745;' : '&#9744;');
        $document->setValue('grace1',$student->grace_period == 1 ? '&#9745;' : '&#9744;');
        $document->setValue('grace2',$student->grace_period == 2 ? '&#9745;' : '&#9744;');
        $document->setValue('grace3',$student->grace_period == 3 ? '&#9745;' : '&#9744;');
        $document->setValue('date_start_grace',$student->date_start_grace_period ? Yii::$app->getFormatter()->asDate($student->date_start_grace_period):'');
        $document->setValue('date_end_grace',$student->date_end_grace_period ? Yii::$app->getFormatter()->asDate($student->date_end_grace_period):'');

        $document->saveAs('uploads/temp.docx');
        Yii::$app->response->sendFile('uploads/temp.docx')->send();
        unlink('uploads/temp.docx');

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
    public function addDocs($model){
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'rasp_act0');
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'rasp_act1');
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'rasp_act2');
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'rasp_act3');
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'dogovor');
        StudentDocs::addDoc($model,"/$model->id_org/$model->id",'rasp_act_otch');
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Students();
        $modelD = new DatesEducationStatus();
        $orgs = Organizations::getOrgs();

        if ($model->load(Yii::$app->request->post())) {
            $model->status=0;
            $model->date_create = date('Y-m-d');
            $model->id_org = Yii::$app->session['id_org'];

            if ($model->save()) {
                $this->addDocs($model);
                $modelD->id_student = $model->id;
                $modelD->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'orgs'=>$orgs,
            // 'id_org'=>Yii::$app->session['id_org']
        ]);
    }
    public function actionDownload($id){
        StudentDocs::download($id);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $orgs = Organizations::getOrgs();

        if ($model->load(Yii::$app->request->post())) {
            if ($this->cans[0] || $this->cans[1])
                $model->status=1;
            if (!$model->dateLastStatus)
                $model->dateLastStatus = new DatesEducationStatus();
            $model->dateLastStatus->id_student = $id;
            $model->dateLastStatus->date_end = !$model->education_status ? date('Y-m-d') : null;
            $this->addDocs($model);
            if ($model->save() and $model->dateLastStatus->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'orgs'=>$orgs,
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

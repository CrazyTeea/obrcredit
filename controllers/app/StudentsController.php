<?php

namespace app\controllers\app;


use app\models\app\Organizations;
use app\models\app\students\DatesEducationStatus;
use app\models\app\students\StudentDocs;
use app\models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Throwable;
use Yii;
use app\models\app\students\Students;
use app\models\app\students\StudentsSearch;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * StudentsController implements the CRUD actions for Students model.
 */
class StudentsController extends AppController
{
   // private $cans;
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

        $studentsExport = Students::find()->where(['id_org'=>$searchModel->id_org]);
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
                return $model->education_status ? "Обучается" : (isset($model->dateLastStatus) and isset($model->dateLastStatus->date_end)) ? Yii::$app->getFormatter()->asDate( $model->dateLastStatus->date_end ).$data : '';
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

        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'name','label'=>"ФИО <br> обучающегося",'encodeLabel'=>false],
            ['attribute'=>'organization','value'=>'organization.short_name','label'=>'Наименование <br> ООВО','encodeLabel'=>false],
            ['attribute'=>'code','label'=>'Код <br> направления <br> подготовки','encodeLabel'=>false],
            ['attribute'=>'education_status','label'=>'Статус <br> обучающегося','encodeLabel'=>false,'content'=>function($model){
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
                return $model->education_status ? "<span class='label label-info'> Обучается</span>" : (isset($model->dateLastStatus) and isset($model->dateLastStatus->date_end)) ? Yii::$app->getFormatter()->asDate($model->dateLastStatus->date_end ).$data : '';
            }],
            ['attribute'=>'grace_period','encodeLabel'=>false,'value'=>
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
                ,'label'=>'Пролонгация<br>льготного<br>периода'
            ],
            ['attribute'=>'date_credit','encodeLabel'=>false,'label'=>'Дата <br> заключения <br> кредитного <br> договора',],
            ['attribute'=>'dateLastStatus','encodeLabel'=>false,'value'=>'dateLastStatus.updated_at','label'=>'Дата <br> изменения <br> данных'],
        ];

        if (!$this->cans[2]) {
            $columns = ArrayHelper::merge( $columns, [
                ['attribute' => 'numberPP','value' => 'numberPP.number','encodeLabel'=>false, 'label' => 'Номер <br> ПП <br> по <br> образовательному <br>кредиту'],
                ['attribute' => 'bank','value'=>'bank.name','encodeLabel'=>false, 'label' => 'Наименование <br> банка <br>или<br> иной <br> кредитной <br>организации'],
                ['attribute' => 'date_status','encodeLabel'=>false, 'format' => 'date', 'label' => 'Дата <br> утрерждения <br> отчета'],
            ] );
        }
        if (!$this->cans[2]) {
            $columns = ArrayHelper::merge( $exportColumns, [
                ['attribute' => 'numberPP','value' => 'numberPP.number', 'label' => 'Номер ПП по образовательному кредиту'],
                ['attribute' => 'bank','value'=>'bank.name', 'label' => 'Наименование банка или иной кредитной организации'],
                ['attribute' => 'date_status', 'format' => 'date', 'label' => 'Дата утрерждения отчета'],
            ] );
        }



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'exportColumns'=>$exportColumns,
            'exportProvider'=>$exportProvider
        ]);
    }
    public function actionApprove(){
        $id_org = Yii::$app->getSession()['id_org'];
        $students = Students::findAll(['id_org'=>$id_org]);
        foreach ($students as $student){
            $student->status = 2;
            $student->date_status = date('Y-m-d');
            $student->save();
        }
        return $this->redirect(['index']);
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
     * @return string|Response
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
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $orgs = Organizations::getOrgs();
        $modelDFlag = false;

        if ($model->load(Yii::$app->request->post())) {
            if ($this->cans[0] || $this->cans[1])
                $model->status=1;
            if (!$model->dateLastStatus){
                $modelD = new DatesEducationStatus();
                $modelD->id_student = $id;
                $modelD->date_end = !$model->education_status ? date('Y-m-d') : null;
                $modelDFlag = $modelD->save();
            }
            else {
                $model->dateLastStatus->id_student = $id;
                $model->dateLastStatus->date_end = !$model->education_status ? date('Y-m-d') : null;
                $modelDFlag = $model->dateLastStatus->save();
            }
            $this->addDocs($model);
            if ($model->save() and $modelDFlag)
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'orgs'=>$orgs,
        ]);
    }


    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
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

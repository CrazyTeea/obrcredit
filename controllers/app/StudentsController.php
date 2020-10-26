<?php

namespace app\controllers\app;


use app\models\app\Banks;
use app\models\app\Files;
use app\models\app\Organizations;
use app\models\app\students\Changes;
use app\models\app\students\DatesEducationStatus;
use app\models\app\students\NumbersPp;
use app\models\app\students\StudentDocumentTypes;
use app\models\app\students\Students;
use app\models\app\students\StudentsHistory;
use app\models\app\students\StudentsHistorySearch;
use app\models\app\students\StudentsSearch;
use app\models\app\students\StudentsSearch2;
use app\models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use function Matrix\trace;

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

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction( $action )
    {
        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null && !Yii::$app->getRequest()->validateCsrfToken()) {
                throw new BadRequestHttpException(Yii::t('yii', 'Не удалось проверить данные.'));
            }
            $this->cans = Yii::$app->session->get('cans' );
            return true;
        }

        return false;
    }

    public function actionHistory($id){
        $model = Students::findOne($id);
        $models = Students::findAll(['name'=>$model->name]);
        return $this->render('history',compact('models'));
    }


    public function actionDp($id){
        $m = Students::findOne($id);
        $m->ext_status = 2;
        $m->save(false);
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionAb($id){
        $m = Students::findOne($id);
        $m->ext_status = 1;
        $m->save(false);
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * Lists all Students models.
     * @param null $id
     * @return mixed
     */
    public function actionIndex( $id = null )
    {
        $this->updateRouteHistory('/app/students/index');
        $searchModel = new StudentsSearch();

        if ( !empty( $id ) )
            Yii::$app->session[ 'id_org' ] = $id;
        if ( !( $this->cans[ 0 ] || $this->cans[ 1 ] ) )
            Yii::$app->session[ 'id_org' ] = User::findIdentity( Yii::$app->user->id )->id_org ?? 1;
        if ( Yii::$app->session[ 'id_org' ] )
            Yii::$app->session[ 'short_name_org' ] = Organizations::findOne( Yii::$app->session[ 'id_org' ] )->name;
        $searchModel->id_org = Yii::$app->session[ 'id_org' ];

        $searchModel->id_bank = Yii::$app->session[ 'id_bank' ];
        $searchModel->id_number_pp = Yii::$app->session[ 'nPP' ];
        $searchModel->month = Yii::$app->session['month'];
        $searchModel->year = Yii::$app->session['year'];

        $searchModel2 = clone $searchModel;
        $searchModel3 = clone $searchModel;

        $searchAbitur = clone $searchModel;
        $searchAbitur->a = true;
        $searchAbitur->d = false;
        $searchDosPog = clone $searchModel;
        $searchDosPog->d = true;
        $searchDosPog->a = false;



        $isApprove = Students::find()->where([
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
            'status'=>1
        ])->count();

        $searchModel->grace = true;
        $searchModel2->osn = true;
        $searchModel3->ender = true;
        $searchModel2->education_status = 0;
        $searchModel2->isEnder = 0;
        $searchModel3->education_status = 0;

        //   $searchModel2->id_bank = Yii::$app->session[ 'id_bank' ];
        //  $searchModel2->id_number_pp = Yii::$app->session[ 'nPP' ];
        //  $searchModel3->id_bank = Yii::$app->session[ 'id_bank' ];
        //   $searchModel3->id_number_pp = Yii::$app->session[ 'nPP' ];

        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        $dataProvider2 = $searchModel2->search( Yii::$app->request->queryParams );
        $dataProvider3 = $searchModel3->search( Yii::$app->request->queryParams );
        $dataProviderA = $searchAbitur->search( Yii::$app->request->queryParams );
        $dataProviderD = $searchDosPog->search( Yii::$app->request->queryParams );


        $searchModel4 = new StudentsHistorySearch();
        $searchModel4->month = $searchModel->month;
        $searchModel4->year = $searchModel->year;
        $searchModel4->id_bank = $searchModel->id_bank;
        $searchModel4->id_number_pp = $searchModel->id_number_pp;
        $searchModel4->org_old = $searchModel->id_org;
        $dataProvider4 = $searchModel4->search(Yii::$app->request->queryParams);

        $studentsExport = Students::find()->where( [
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
        ]);

        // var_dump($studentsExport->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit();

        $exportProvider = new ActiveDataProvider( ['query' => $studentsExport, 'pagination' => false] );
        $views['index']['search'] = $searchModel;
        $views['index']['provider'] = $dataProvider;
        $views['index']['export'] = $exportProvider;
        $views['index']['isApprove'] = $isApprove;


        $views['otch']['search'] = $searchModel2;
        $views['otch']['provider'] = $dataProvider2;

        $views['ender']['search'] = $searchModel3;
        $views['ender']['provider'] = $dataProvider3;

        $views['A']['search'] = $searchAbitur;
        $views['A']['provider'] = $dataProviderA;

        $views['D']['search'] = $searchDosPog;
        $views['D']['provider'] = $dataProviderD;

        $views['keks']['search'] = $searchModel4;
        $views['keks']['provider'] = $dataProvider4;

        return $this->render( 'index',compact('views') );
    }


    /**
     * @param $id
     * @param $nPP
     * @param $month
     * @return string|Response
     */
    public function actionByBank( $id, $nPP, $month )
    {
        $this->updateRouteHistory('/app/students/by-bank');
        if (!Yii::$app->session->has('year'))
            return $this->redirect(['app/main/index']);

        Yii::$app->session->set('month',$month);

        $searchModel = new StudentsSearch();

        Yii::$app->session->set('id_bank',$id);

        Yii::$app->session->set('nPP',$nPP);


        if ( !( $this->cans[ 0 ] || $this->cans[ 1 ] ) )
            Yii::$app->session[ 'id_org' ] = User::findIdentity( Yii::$app->user->id )->id_org ?? 1;
        Yii::$app->session[ 'short_name_org' ] = ($org = Organizations::findOne( Yii::$app->session[ 'id_org' ] )) ? $org->name : '';
        $searchModel->id_org = Yii::$app->session[ 'id_org' ];

        $searchModel->month  = Yii::$app->session['month'];
        $searchModel->year = Yii::$app->session['year'];

        $searchModel->id_bank = Yii::$app->session[ 'id_bank' ];
        $searchModel->id_number_pp = Yii::$app->session[ 'nPP' ];

        $searchModel->ext_status = 0;

        $searchModel2 = clone $searchModel;
        $searchModel3 = clone $searchModel;
        $searchAbitur = clone $searchModel;
        $searchAbitur->a = true;
        $searchAbitur->d = false;
        $searchDosPog = clone $searchModel;
        $searchDosPog->d = true;
        $searchDosPog->a = false;

        $searchModel->grace = true;
        $searchModel2->osn = true;
        $searchModel3->ender = true;
        $searchModel2->education_status = 0;
        $searchModel2->isEnder = 0;
        $searchModel3->education_status = 0;

        //  $searchModel2->id_bank = Yii::$app->session[ 'id_bank' ];
        //  $searchModel2->id_number_pp = Yii::$app->session[ 'nPP' ];
        // $searchModel3->id_bank = Yii::$app->session[ 'id_bank' ];
        // $searchModel3->id_number_pp = Yii::$app->session[ 'nPP' ];

        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        $dataProvider2 = $searchModel2->search( Yii::$app->request->queryParams );
        $dataProvider3 = $searchModel3->search( Yii::$app->request->queryParams );
        $dataProviderA = $searchAbitur->search( Yii::$app->request->queryParams );
        $dataProviderD = $searchDosPog->search( Yii::$app->request->queryParams );

        $searchModel4 = new StudentsHistorySearch();
        $searchModel4->month = $searchModel->month;
        $searchModel4->year = $searchModel->year;
        $searchModel4->id_bank = $searchModel->id_bank;
        $searchModel4->id_number_pp = $searchModel->id_number_pp;
        $searchModel4->org_old = $searchModel->id_org;
        $dataProvider4 = $searchModel4->search(Yii::$app->request->queryParams);

        $isApprove = Students::find()->where([
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
            'status'=>1
        ])->all();

        $studentsExport = Students::find()->where( [
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
        ]);
        $exportProvider = new ActiveDataProvider( ['query' => $studentsExport, 'pagination' => false] );

        $views['index']['search'] = $searchModel;
        $views['index']['provider'] = $dataProvider;
        $views['index']['export'] = $exportProvider;
        $views['index']['isApprove'] = $isApprove;

        $views['otch']['search'] = $searchModel2;
        $views['otch']['provider'] = $dataProvider2;

        $views['ender']['search'] = $searchModel3;
        $views['ender']['provider'] = $dataProvider3;

        $views['A']['search'] = $searchAbitur;
        $views['A']['provider'] = $dataProviderA;

        $views['D']['search'] = $searchDosPog;
        $views['D']['provider'] = $dataProviderD;

        $views['keks']['search'] = $searchModel4;
        $views['keks']['provider'] = $dataProvider4;

        return $this->render( 'index',compact('views'));
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionReturn($id){
        if ($post = Yii::$app->request->post()){
            $model = $this->findModel($id);
            $new_id = 0;
            if ($model and $model->load($post)){
                $date = explode('-',$model->date_start);
                foreach (xrange($date[0],2020) as $year){
                    foreach (xrange($date[1],12) as $month){
                        $student = new Students();
                        $student->date_start = "{$year}-{$month}-01";
                        $student->name=$model->name;
                        $student->code=$model->code;
                        $student->date_credit = $model->date_credit;
                        $student->id_bank = $model->id_bank;
                        $student->id_number_pp = $model->id_number_pp;
                        $student->education_status = 1;
                        $student->system_status = 1;
                        $student->save(false);
                        $new_id=$student->id;
                    }
                    $date[1] = 1;
                }
            }
            return $this->redirect(['view','id'=>$new_id]);
        }
        return null;
    }

    /**
     * @param null $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionApprove( $id = null)
    {
        $this->updateRouteHistory('/app/students/approve');
        if (is_null($id)) {
            $nPP = Yii::$app->session->get( 'nPP' );
            $id_org = Yii::$app->getSession()[ 'id_org' ];
            $month = Yii::$app->getSession()[ 'month' ];
            $year = Yii::$app->getSession()[ 'year' ];
            $id_bank = Yii::$app->getSession()[ 'id_bank' ];
            $students = Students::find()->where( ['id_org' => $id_org, 'MONTH(date_start)' => $month,'status'=>1, 'YEAR(date_start)' => $year, 'id_bank' => $id_bank, 'id_number_pp' => $nPP] )->all();
            $transaction = Yii::$app->db->beginTransaction();
            $save = true;
            foreach ($students as $student) {

                    $student->status = 2;
                    $student->date_status = date( "Y-m-d" );
                    $save &=$student->save(false);

            }
            if ($save) $transaction->commit(); else $transaction->rollBack();
            return $this->redirect( Yii::$app->request->referrer );
        }

        $student = $this->findModel( $id );
        //$docTypes = StudentDocumentTypes::getActive()->all();

        if ($student) {
            $student->status = 2;
            $student->date_status = date( "Y-m-d" );
            $student->save(false);
        }
        return $this->redirect( ['view','id'=>$id] );
    }

    /**
     * @param null $id
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws InvalidConfigException
     */
    public function actionExport( $id = null )
    {
        $student = Students::findOne( $id );
        $document = new TemplateProcessor( 'templates/export.docx' );
        $document->setValue( 'fio', $student->name );
        $document->setValue( 'code', $student->code );
        $document->setValue( 'e_status', $student->education_status ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie1', $student->osnovanie == 1 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie2', $student->osnovanie == 2 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie3', $student->osnovanie == 3 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie4', $student->osnovanie == 4 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie5', $student->osnovanie == 5 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'osnovanie6', $student->osnovanie == 6 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'grace1', $student->grace_period == 1 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'grace2', $student->grace_period == 2 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'grace3', $student->grace_period == 3 ? '&#9745;' : '&#9744;' );
        $document->setValue( 'date_start_grace', $student->date_start_grace_period ? Yii::$app->getFormatter()->asDate( $student->date_start_grace_period ) : '' );
        $document->setValue( 'date_end_grace', $student->date_end_grace_period ? Yii::$app->getFormatter()->asDate( $student->date_end_grace_period ) : '' );

        $document->saveAs( 'uploads/temp.docx' );
        Yii::$app->response->sendFile( 'uploads/temp.docx' )->send();
        unlink( 'uploads/temp.docx' );

    }

    /**
     * Displays a single Students model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView( $id )
    {
        $this->updateRouteHistory('/app/students/view');
        $docTypes = StudentDocumentTypes::getActive()->all();
        $model = $this->findModel( $id );

        $subQ = Students::find()->select(['id','min(date_start) min_date','name','date_credit'])->where(['name'=>$model->name,'date_credit'=>$model->date_credit]);
        $minS = Students::find()->from('students t1')->join('JOIN',['t2'=>$subQ],'t2.min_date=t1.date_start and t2.name=t1.name and t2.date_credit=t1.date_credit')->one();
        $history = new StudentsHistory();
        $is_in_history = false;
        if ($minS){
            $history =  StudentsHistory::findOne(['id_student'=>$minS->id]);
            if (!$history){
                $history = new StudentsHistory();
            }else $is_in_history = true;
        }
        //  $history = ($minS) ?  ? $st : new StudentsHistory() : new StudentsHistory();
        $changes = ArrayHelper::map(Changes::find()->select(['id','change','system_status'])->where(['system_status'=>1])->all(),'id','change');
        if ($history->load(Yii::$app->request->post()))
        {
            //  var_dump($history);exit();
            $students = Students::findAll(['name'=>$model->name,'code'=>$model->code,'date_credit'=>$model->date_credit]);
            foreach ($students as $st){
                $st->system_status = 0;
                $st->save();
            }
            $history->id_student = $minS->id;
            $history->id_user_from = Yii::$app->user->getId();
            if ($history->save())
                Yii::$app->session->setFlash('history', 'Обучающийся отправлен в журнал изменений.');
            else
                Yii::$app->session->setFlash('history', 'Произошла ошибка при добавлении обучающийся в журнал изменений.');


        }
        Yii::$app->getSession()->set('id_bank',$model->id_bank);
        Yii::$app->getSession()->set('id_org',$model->id_org);
        Yii::$app->getSession()->set( 'nPP' ,$model->id_number_pp);
        Yii::$app->getSession()->set('month',date('n',strtotime($model->date_start)));
        Yii::$app->getSession()->set('year',date('Y',strtotime($model->date_start)));
        $route = null;
        if (!Yii::$app->getSession()->get('cans')[2]){
            $route = ['index','id'=>$model->id_org];
        }
        else{
            $route = ['by-bank','id'=>$model->id_bank,'nPP'=>$model->id_number_pp,'month'=>Yii::$app->getSession()->get('month')];
        }

        $route = Url::to($route);


        return $this->render( 'view',compact('model','docTypes','history','changes','route','is_in_history'));
    }

    /**
     * Finds the Students model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Students the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if ( ( $model = Students::findOne( $id ) ) !== null ) {
            return $model;
        }
        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }


    /**
     * @param $id
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate( $id )
    {
        $this->updateRouteHistory('/app/students/create');
        Yii::$app->session[ 'id_org' ] = $id;
        $model = new Students();
        $docTypes = StudentDocumentTypes::getActive()->all();
        $modelD = new DatesEducationStatus();
        $orgs = Organizations::getOrgs();
        $file = new Files();

        if ( $model->load( Yii::$app->request->post() ) ) {
            if ($model->perevod)
                $model->education_status = 1;
            //$model->status = 0;
            $model->date_create = date( 'Y-m-d' );
            $model->id_org = $id;

            if ( $model->save() ) {
                $modelD->id_student = $model->id;
                if ($model->addStudentDocs($file,$docTypes) and $modelD->save())
                    return $this->redirect( ['view', 'id' => $model->id] );
            }
        }

        return $this->render( 'create', compact('model','orgs','file','docTypes'));
    }


    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAddToHistory($id){
        $this->updateRouteHistory('/app/students/add-to-history');
        $model = $this->findModel($id);
        $models = Students::find()->where(['name'=>$model->name,'date_credit'=>$model->date_credit])->all();
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
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionOtch($id){
        $model = Students::findOne($id);
        $model->education_status = 0;
        $model->save(false);
        $models = Students::find()->where(['name'=>$model->name,'id_org'=>$model->id_org])->andWhere(['>=','date_start',$model->date_start])->all();
        foreach ($models as $model2){
            $model2->sytem_status = 0;

            $govno = StudentsHistory::findOne(['id_student'=>$model2->id]) ?? new StudentsHistory();
            if ($govno->isNewRecord){
                $govno->id_student = $model2->id;
                $govno->id_change = 1;
                $govno->save();
            }


            $model2->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);

    }
    public function actionVip($id){
        $model = Students::findOne($id);
        $model->education_status = $model->osnovanie = 0;
        $model->isEnder = 1;
        $model->date_ender = $model->date_start;
        $model->save(false);
        $models = Students::find()->where(['name'=>$model->name,'id_org'=>$model->id_org])->andWhere(['>','date_start',$model->date_start])->all();
        foreach ($models as $model2){
            $model2->sytem_status = 0;

            $govno = StudentsHistory::findOne(['id_student'=>$model2->id]) ?? new StudentsHistory();
            if ($govno->isNewRecord){
                $govno->id_student = $model2->id;
                $govno->id_change = 1;
                $govno->save();
            }

            $model2->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionActive($id){
        $model = Students::findOne($id);
        $model->system_status = 1;
        $model->education_status = 1;
        $model->osnovanie = $model->grace_period = $model->isEnder = 0;
        $model->save(false);
        $models = Students::find()->select(['id'])->where(['name'=>$model->name,'id_org'=>$model->id_org])->column();
        StudentsHistory::deleteAll(['id_student'=>$models]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionNotFound($id){
        $model = Students::findOne($id);
        $students = Students::findAll(['name'=>$model->name,'date_credit'=>$model->date_credit]);
        foreach ($students as $st){
            $st->system_status = 0;
            $govno = StudentsHistory::findOne(['id_student'=>$st->id]) ?? new StudentsHistory();
            if ($govno->isNewRecord){
                $govno->id_student = $st->id;
                $govno->id_change = 1;
                $govno->save();
            }
            $st->id_org_old= $st->id_org;
            $st->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate( $id )
    {
        $this->updateRouteHistory('/app/students/update');
        $model = $this->findModel( $id );
        $model->old_code = $model->code;
        $orgs = Organizations::getOrgs();
        $docTypes = StudentDocumentTypes::getActive()->all();
        $file = new Files();
        //  $modelDFlag = false;

        if ( $model->load( Yii::$app->request->post() ) ) {

            if ($model->old_code != $model->code){
                $students = Students::find()->where(['name'=>$model->name,'date_credit'=>$model->date_credit]);
                $students->andWhere(['<>','id',$model->id]);
                $students = $students->all();
                foreach ($students as $key => $s){
                    $s->old_code = $s->code;
                    $s->code = $model->code;
                    $s->save(false);
                }

            }
            if ( $this->cans[ 0 ] || $this->cans[ 1 ] )
                $model->status = 1;
            if ( !$model->dateLastStatus ) {
                $modelD = new DatesEducationStatus();
                $modelD->id_student = $id;
                $modelD->date_end = !$model->education_status ? date( 'Y-m-d' ) : null;
                $modelDFlag = $modelD->save();
            } else {
                $model->dateLastStatus->id_student = $id;
                $model->dateLastStatus->date_end = !$model->education_status ? date( 'Y-m-d' ) : null;
                $modelDFlag = $model->dateLastStatus->save();
            }
            if ( $model->save() and $modelDFlag and $model->addStudentDocs($file,$docTypes)) {
                return $this->redirect( ['view', 'id' => $model->id] );
            }
        }

        return $this->render( 'update',compact('model','orgs','file','docTypes') );
    }

    /**
     * @param $id
     * @param $desc
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteDoc($id, $desc){
        $this->updateRouteHistory('/app/students/delete-doc');
        $st = $this->findModel($id);
        $st->deleteDocument($desc);
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionToHistory($id){
        $this->updateRouteHistory('/app/students/to-history');
        $student = $this->findModel($id);
        $students = Students::find()->where(['name'=>$student->name,'code'=>$student->code,'date_credit'=>$student->date_credit])->all();
        foreach ($students as $item){
            $item->system_status=0;
            $item->save(false);
        }
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete( $id )
    {
        $this->updateRouteHistory('/app/students/delete');
        $s=$this->findModel( $id );
        $s->system_status=0;
        $s->save(false);

        return $this->redirect(['delete-view']);
    }

    /**
     * @return string
     */
    public function actionDeleteView(){
        $this->updateRouteHistory('/app/students/delete-view');
        $model = new StudentsSearch2();
        $provider = $model->search(Yii::$app->request->queryParams);
        $orgs = Organizations::find()->where(['system_status'=>1])->all();
        $nums = NumbersPp::find()->all();
        $banks = Banks::find()->all();
        $orgs = ArrayHelper::map($orgs,'id','name');
        $nums = ArrayHelper::map($nums,'id','number');
        $banks = ArrayHelper::map($banks,'id','name');

        return $this->render('delete-view',compact('model','provider','orgs','nums','banks'));
    }

}

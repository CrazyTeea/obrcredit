<?php

namespace app\controllers\app;


use app\models\app\Files;
use app\models\app\Organizations;
use app\models\app\students\DatesEducationStatus;
use app\models\app\students\StudentDocumentList;
use app\models\app\students\StudentDocumentTypes;
use app\models\app\students\Students;
use app\models\app\students\StudentsSearch;
use app\models\User;
use phpDocumentor\Reflection\Types\Mixed_;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

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



    /**
     * Lists all Students models.
     * @param null $id
     * @return mixed
     */
    public function actionIndex( $id = null )
    {
        $searchModel = new StudentsSearch();





        if ( !empty( $id ) )
            Yii::$app->session[ 'id_org' ] = $id;
        if ( !( $this->cans[ 0 ] || $this->cans[ 1 ] ) )
            Yii::$app->session[ 'id_org' ] = User::findIdentity( Yii::$app->user->id )->id_org ? User::findIdentity( Yii::$app->user->id )->id_org : 1;
        if ( Yii::$app->session[ 'id_org' ] )
            Yii::$app->session[ 'short_name_org' ] = Organizations::findOne( Yii::$app->session[ 'id_org' ] )->name;
        $searchModel->id_bank = Yii::$app->session[ 'id_bank' ];
        $searchModel->id_org = Yii::$app->session[ 'id_org' ];
        $searchModel->id_number_pp = Yii::$app->session[ 'nPP' ];
        $searchModel->month = Yii::$app->session['month'];
        $searchModel->year = Yii::$app->session['year'];

        $isApprove = Students::find()->where([
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
            'status'=>1
        ])->all();



        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        $studentsExport = Students::find()->where( [
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
            ] );
        $exportProvider = new ActiveDataProvider( ['query' => $studentsExport, 'pagination' => false] );

        $exportColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'name', 'label' => "ФИО обучающегося"],
            ['attribute' => 'organization', 'value' => 'organization.short_name', 'label' => 'Наименование ООВО'],
            ['attribute' => 'code', 'label' => 'Код направления подготовки'],
            ['attribute' => 'isEnder', 'label' => 'Выпускник (завершено обучение в образовательной организации)',
                'value'=>
                    function($model){
                        return $model->isEnder ? 'Выпускник '.Yii::$app->getFormatter()->asDate($model->date_ender):'';
                    }
            ],
            ['attribute' => 'education_status', 'label' => 'Статус обучающегося', 'content' => function ( $model ) {
                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->osnovanie ) ? $model->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->osnovanie ) {
                    case 1:
                    case 2:
                    case 3:
                    {
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:
                    {
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:
                    {
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:
                    {
                        $data = "";
                        break;
                    }
                }
                $date = null;
                if ( isset( $model->dateLastStatus ) and isset( $model->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';

                return $model->education_status ? $model->perevod ? 'Переведен на бюджет' : "Обучается" : $dta;
            }],
            ['attribute' => 'grace_period', 'value' =>
                function ( $model ) {
                    $data = "";
                    switch ( $model->grace_period ) {
                        case 1:
                        {
                            $date = ( $model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period1 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period1 ) : '';
                            $data = Students::getGracePeriod()[ 1 ] . "($date)";
                            break;
                        }
                        case 2:
                        {
                            $date = ( $model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period2 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period2 ) : '';
                            $data = Students::getGracePeriod()[ 2 ] . "($date)";
                            break;
                        }
                        case 3:
                        {
                            $date = ( $model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period3 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period3 ) : '';
                            $data = Students::getGracePeriod()[ 3 ] . "($date)";
                            break;
                        }
                        default:
                        {
                            $data = '';
                            break;
                        }
                    }
                    return $data;
                }
                , 'label' => 'Пролонгация льготного периода'
            ],
            ['attribute' => 'date_credit', 'label' => 'Дата заключения кредитного договора',],
            ['attribute' => 'dateLastStatus', 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата изменения данных'],
        ];

        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'name', 'label' => "ФИО <br> обучающегося", 'encodeLabel' => false],
            ['attribute' => 'organization', 'value' => 'organization.short_name', 'label' => 'Наименование <br> ООВО', 'encodeLabel' => false],
            ['attribute' => 'code', 'label' => 'Код <br> направления <br> подготовки', 'encodeLabel' => false],
            ['attribute' => 'education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false, 'content' => function ( $model ) {

                if ($model->isEnder)
                    return "<span class='label label-danger'>Выпускник</span><br>".Yii::$app->getFormatter()->asDate($model->date_ender);

                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->osnovanie ) ? $model->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->osnovanie ) {
                    case 1:
                    case 2:
                    case 3:
                    {
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:
                    {
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:
                    {
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:
                    {
                        $data = "";
                        break;
                    }
                }

                $date = null;
                if ( isset( $model->dateLastStatus ) and isset( $model->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';

                return ( $model->education_status ) ? $model->perevod ? "<span class='label label-info'>Переведен на бюджет</span>" : "<span class='label label-info'> Обучается</span>" : $dta;
            }
            ],
            ['attribute' => 'grace_period', 'encodeLabel' => false, 'value' =>
                function ( $model ) {
                    $data = "";
                    switch ( $model->grace_period ) {
                        case 1:
                        {
                            $date = ( $model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period1 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period1 ) : '';
                            $data = Students::getGracePeriod()[ 1 ] . "($date)";
                            break;
                        }
                        case 2:
                        {
                            $date = ( $model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period2 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period2 ) : '';
                            $data = Students::getGracePeriod()[ 2 ] . "($date)";
                            break;
                        }
                        case 3:
                        {
                            $date = ( $model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period3 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period3 ) : '';
                            $data = Students::getGracePeriod()[ 3 ] . "($date)";
                            break;
                        }
                        default:
                        {
                            $data = '';
                            break;
                        }
                    }
                    return $data;
                }
                , 'label' => 'Пролонгация<br>льготного<br>периода'
            ],
            ['attribute' => 'date_credit', 'encodeLabel' => false, 'label' => 'Дата <br> заключения <br> кредитного <br> договора',],
            ['attribute' => 'dateLastStatus', 'encodeLabel' => false, 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата <br> изменения <br> данных'],
        ];

        if ( !$this->cans[ 2 ] ) {
            $columns = ArrayHelper::merge( $columns, [
                ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'encodeLabel' => false, 'label' => 'Номер <br> ПП <br> по <br> образовательному <br>кредиту'],
                ['attribute' => 'bank', 'value' => 'bank.name', 'encodeLabel' => false, 'label' => 'Наименование <br> банка <br>или<br> иной <br> кредитной <br>организации'],
                ['attribute' => 'date_status', 'encodeLabel' => false, 'format' => 'date', 'label' => 'Дата <br> утверждения <br> отчета'],
            ] );

            $exportColumns = ArrayHelper::merge( $exportColumns, [
                ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'label' => 'Номер ПП по образовательному кредиту'],
                ['attribute' => 'bank', 'value' => 'bank.name', 'label' => 'Наименование банка или иной кредитной организации'],
                ['attribute' => 'date_status', 'format' => 'date', 'label' => 'Дата утверждения отчета'],
            ] );
        }


        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'exportColumns' => $exportColumns,
            'exportProvider' => $exportProvider,
            'isApprove'=>$isApprove
        ] );
    }



    public function actionByBank( $id, $nPP,$month )
    {
        if (!Yii::$app->session->has('year'))
            return $this->redirect(['app/main/index']);

        Yii::$app->session->set('month',$month);

        $searchModel = new StudentsSearch();
        $searchModel->id_bank = $id;
        Yii::$app->session->set('id_bank',$searchModel->id_bank);

        Yii::$app->session->set('nPP',$nPP);

        $searchModel->month = Yii::$app->session->get('month');
        $searchModel->year = Yii::$app->session->get('year');
        $searchModel->id_number_pp = $nPP;

        if ( !( $this->cans[ 0 ] || $this->cans[ 1 ] ) )
            Yii::$app->session[ 'id_org' ] = User::findIdentity( Yii::$app->user->id )->id_org ? User::findIdentity( Yii::$app->user->id )->id_org : 1;
        Yii::$app->session[ 'short_name_org' ] = ($org = Organizations::findOne( Yii::$app->session[ 'id_org' ] )) ? $org->name : '';

        $searchModel->id_org = Yii::$app->session->get('id_org');

        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        $isApprove = Students::find()->where([
            'id_bank'=>$searchModel->id_bank,
            'MONTH(date_start)'=>$searchModel->month,
            'YEAR(date_start)'=>$searchModel->year,
            'id_number_pp'=>$searchModel->id_number_pp,
            'id_org'=>$searchModel->id_org,
            'status'=>1
        ])->all();

        $studentsExport = Students::find()->where( ['id_org' => $searchModel->id_org, 'MONTH(date_start)' => $searchModel->month, 'YEAR(date_start)' => Yii::$app->session[ 'year' ]] );
        $exportProvider = new ActiveDataProvider( ['query' => $studentsExport, 'pagination' => false] );

        $exportColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'name', 'label' => "ФИО обучающегося"],
            ['attribute' => 'organization', 'value' => 'organization.short_name', 'label' => 'Наименование ООВО'],
            ['attribute' => 'code', 'label' => 'Код направления подготовки'],
            ['attribute' => 'education_status', 'label' => 'Статус обучающегося', 'content' => function ( $model ) {
                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->osnovanie ) ? $model->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->osnovanie ) {
                    case 1:
                    case 2:
                    case 3:
                    {
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:
                    {
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:
                    {
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:
                    {
                        $data = "";
                        break;
                    }
                }
                $date = null;
                if ( isset( $model->dateLastStatus ) and isset( $model->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';

                return $model->education_status ? $model->perevod ? 'Переведен на бюджет' : "Обучается" : $dta;
            }],
            ['attribute' => 'grace_period', 'value' =>
                function ( $model ) {
                    $data = "";
                    switch ( $model->grace_period ) {
                        case 1:
                        {
                            $date = ( $model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period1 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period1 ) : '';
                            $data = Students::getGracePeriod()[ 1 ] . "($date)";
                            break;
                        }
                        case 2:
                        {
                            $date = ( $model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period2 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period2 ) : '';
                            $data = Students::getGracePeriod()[ 2 ] . "($date)";
                            break;
                        }
                        case 3:
                        {
                            $date = ( $model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period3 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period3 ) : '';
                            $data = Students::getGracePeriod()[ 3 ] . "($date)";
                            break;
                        }
                        default:
                        {
                            $data = '';
                            break;
                        }
                    }
                    return $data;
                }
                , 'label' => 'Пролонгация льготного периода'
            ],
            ['attribute' => 'date_credit', 'label' => 'Дата заключения кредитного договора',],
            ['attribute' => 'dateLastStatus', 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата изменения данных'],
        ];

        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'name', 'label' => "ФИО <br> обучающегося", 'encodeLabel' => false],
            ['attribute' => 'organization', 'value' => 'organization.short_name', 'label' => 'Наименование <br> ООВО', 'encodeLabel' => false],
            ['attribute' => 'code', 'label' => 'Код <br> направления <br> подготовки', 'encodeLabel' => false],
            ['attribute' => 'education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false, 'content' => function ( $model ) {
                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->osnovanie ) ? $model->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->osnovanie ) {
                    case 1:
                    case 2:
                    case 3:
                    {
                        $data = "(Пункт 20 $os)";
                        break;
                    }
                    case 4:
                    case 5:
                    {
                        $data = "(Пункт 21 $os)";
                        break;
                    }
                    case 6:
                    {
                        $data = "(Пункт 22 $os)";
                        break;
                    }
                    default:
                    {
                        $data = "";
                        break;
                    }
                }

                $date = null;
                if ( isset( $model->dateLastStatus ) and isset( $model->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';
                if ($model->isEnder)
                    return "<span class='label label-info'>Выпускник</span><br>".Yii::$app->formatter->asDate($model->date_ender);

                return ( $model->education_status ) ? $model->perevod ? "<span class='label label-info'>Переведен на бюджет</span>" : "<span class='label label-info'> Обучается</span>" : $dta;
            }
            ],
            ['attribute' => 'grace_period', 'encodeLabel' => false, 'value' =>
                function ( $model ) {
                    $data = "";
                    switch ( $model->grace_period ) {
                        case 1:
                        {
                            $date = ( $model->date_start_grace_period1 and $model->date_end_grace_period1 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period1 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period1 ) : '';
                            $data = Students::getGracePeriod()[ 1 ] . "($date)";
                            break;
                        }
                        case 2:
                        {
                            $date = ( $model->date_start_grace_period2 and $model->date_end_grace_period2 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period2 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period2 ) : '';
                            $data = Students::getGracePeriod()[ 2 ] . "($date)";
                            break;
                        }
                        case 3:
                        {
                            $date = ( $model->date_start_grace_period3 and $model->date_end_grace_period3 ) ?
                                Yii::$app->getFormatter()->asDate( $model->date_start_grace_period3 ) . '-' . Yii::$app->getFormatter()->asDate( $model->date_end_grace_period3 ) : '';
                            $data = Students::getGracePeriod()[ 3 ] . "($date)";
                            break;
                        }
                        default:
                        {
                            $data = '';
                            break;
                        }
                    }
                    return $data;
                }
                , 'label' => 'Пролонгация<br>льготного<br>периода'
            ],
            ['attribute' => 'date_credit', 'encodeLabel' => false, 'label' => 'Дата <br> заключения <br> кредитного <br> договора',],
            ['attribute' => 'dateLastStatus', 'encodeLabel' => false, 'value' => 'dateLastStatus.updated_at', 'label' => 'Дата <br> изменения <br> данных'],
        ];

        if ( !$this->cans[ 2 ] ) {
            $columns = ArrayHelper::merge( $columns, [
                ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'encodeLabel' => false, 'label' => 'Номер <br> ПП <br> по <br> образовательному <br>кредиту'],
                ['attribute' => 'bank', 'value' => 'bank.name', 'encodeLabel' => false, 'label' => 'Наименование <br> банка <br>или<br> иной <br> кредитной <br>организации'],
                ['attribute' => 'date_status', 'encodeLabel' => false, 'format' => 'date', 'label' => 'Дата <br> утверждения <br> отчета'],
            ] );
            $exportColumns = ArrayHelper::merge( $exportColumns, [
                ['attribute' => 'numberPP', 'value' => 'numberPP.number', 'label' => 'Номер ПП по образовательному кредиту'],
                ['attribute' => 'bank', 'value' => 'bank.name', 'label' => 'Наименование банка или иной кредитной организации'],
                ['attribute' => 'date_status', 'format' => 'date', 'label' => 'Дата утверждения отчета'],
            ] );
        }


        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'exportColumns' => $exportColumns,
            'exportProvider' => $exportProvider,
            'isApprove'=>$isApprove
        ] );
    }

    public function actionApprove()
    {
        $nPP = Yii::$app->session->get('nPP');
        $id_org = Yii::$app->getSession()[ 'id_org' ];
        $month = Yii::$app->getSession()['month'];
        $year = Yii::$app->getSession()['year'];
        $id_bank = Yii::$app->getSession()[ 'id_bank' ];
        $students = Students::find()->where( ['id_org' => $id_org,'MONTH(date_start)'=>$month,'YEAR(date_start)'=>$year,'id_bank'=>$id_bank,'id_number_pp'=>$nPP] )->all();

        foreach ($students as $student) {
            $student->status = 2;
            $student->date_status = date( 'Y-m-d' );
            $student->save(false);
        }
        return $this->redirect( ['by-bank', 'id' => Yii::$app->getSession()[ 'id_bank' ], 'month' => Yii::$app->getSession()[ 'month' ], 'nPP'=>$nPP] );
    }

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
        $docTypes = StudentDocumentTypes::getActive()->all();
        return $this->render( 'view', [
            'model' => $this->findModel( $id ),
            'docTypes'=>$docTypes
        ] );
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
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate( $id )
    {
        Yii::$app->session[ 'id_org' ] = $id;
        $model = new Students();
        $docTypes = StudentDocumentTypes::getActive()->all();
        $modelD = new DatesEducationStatus();
        $orgs = Organizations::getOrgs();
        $file = new Files();

        if ( $model->load( Yii::$app->request->post() ) ) {
            //$model->status = 0;
            $model->date_create = date( 'Y-m-d' );
            $model->id_org = $id;

            if ( $model->save() ) {
                $modelD->id_student = $model->id;
                if ($this->addStudentDocs($model,$file,$docTypes) and $modelD->save())
                    return $this->redirect( ['view', 'id' => $model->id] );
            }
        }

        return $this->render( 'create', compact('model','orgs','file','docTypes'));
    }

    /**
     * @param Students $student
     * @param Files $file
     * @param array $studentDocTypes
     * @return bool
     * @throws \yii\base\Exception
     */
    private function addStudentDocs( Students $student,Files $file, array $studentDocTypes){

        $done = true;
        foreach ($studentDocTypes as $studentDocType){
            $instance = UploadedFile::getInstance($file,"[$studentDocType->descriptor]file");
            if ($instance){
                $studentDoc = new StudentDocumentList();
                if (!$studentDoc->add($file,$instance,$student,$studentDocType->id)){
                    $done=false;
                    break;
                }
            }
        }
        return $done;
    }

    private function getStudentDoc($value){
        $document = null;
        switch (gettype($value)){
            case 'integer':{
                $document = StudentDocumentList::findOne($value);
                break;
            }
            case 'string':{
                $document = StudentDocumentList::find()->joinWith(['type'])->where(['descriptor'=>$value])->all();
                break;
            }
        }
        return ($document) ? $document : 'Документ не найден';
    }


    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate( $id )
    {

        $model = $this->findModel( $id );
        $orgs = Organizations::getOrgs();
        $docTypes = StudentDocumentTypes::getActive()->all();
        $file = new Files();
        $modelDFlag = false;

        if ( $model->load( Yii::$app->request->post() ) ) {
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
            if ( $model->save() and $modelDFlag ) {

                $month0 = date('m',strtotime($model->date_start));
                for ($year = date('Y',strtotime($model->date_start));$year<=2021;$year++){
                    for ($month = $month0;$month<=12;$month++){
                        $sts = Students::find()->where([
                            'id_org'=>$model->id_org,'YEAR(date_start)'=>$year,'MONTH(date_start)'=>$month,
                            'name'=>$model->name,'code'=>$model->code
                        ])->all();
                        if ($sts){
                            foreach ($sts as $st){
                                $st->education_status = $model->education_status;
                                $st->osnovanie = $model->osnovanie;
                                $st->grace_period = $model->grace_period;
                                $st->date_start_grace_period1 = $model->date_start_grace_period1;
                                $st->date_start_grace_period2 =$model->date_start_grace_period2;
                                $st->date_end_grace_period2 =$model->date_end_grace_period2;
                                $st->date_start_grace_period3 = $model->date_start_grace_period3 ;
                                $st->date_end_grace_period3 =$model->date_end_grace_period3;
                                $st->perevod = $model->perevod;
                                $st->isEnder = $model->isEnder;
                                $st->date_ender = $model->date_ender;

                                if (!$st->dateLastStatus){
                                    $date = new DatesEducationStatus();
                                    $date->id_student = $st->id;
                                    $date->date_end = date('Y-m-d');
                                    $date->save(false);
                                }

                                $st->save(false);
                            }
                        }
                    }
                    $month0 = 1;
                }

                if ($this->addStudentDocs($model,$file,$docTypes) and $modelDFlag)
                    return $this->redirect( ['view', 'id' => $model->id] );
            }
        }

        return $this->render( 'update',compact('model','orgs','file','docTypes') );
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete( $id )
    {
        $this->findModel( $id )->delete();

        return $this->redirect( ['index'] );
    }

}

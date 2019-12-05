<?php

use app\models\app\students\Students;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'student.name','label'=>'ФИО<br>обучающегося','encodeLabel' => false],
            ['attribute'=>'student.code','label'=>'Код<br>направления','encodeLabel' => false],
            ['attribute' => 'student.education_status', 'format' => 'raw', 'label' => 'Статус <br> обучающегося', 'encodeLabel' => false,
                'content' => function ( $model ) {
                $os = mb_substr( Students::getOsnovanie()[ !empty( $model->student->osnovanie ) ? $model->student->osnovanie : 0 ], 0, 50 );
                $data = "";
                switch ( $model->student->osnovanie ) {
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
                if ( isset( $model->student->dateLastStatus ) and isset( $model->student->dateLastStatus->date_end ) )
                    $date = Yii::$app->getFormatter()->asDate( $model->student->dateLastStatus->date_end );

                $dta = ( $date ) ? "$date $data" : '';
                if ($model->student->isEnder)
                    return "<span class='label label-info'>Выпускник</span><br>".Yii::$app->formatter->asDate($model->student->date_ender);

                return ( $model->student->education_status ) ? $model->student->perevod ? "<span class='label label-info'>Переведен на бюджет</span>" : "<span class='label label-info'> Обучается</span>" : $dta;
            }
            ],
            ['attribute'=>'student.date_credit','label'=>'Дата заключения<br>кредитного договора','encodeLabel' => false],
            ['attribute'=>'student.numberPP.number','label'=>'Номер<br>пп','encodeLabel' => false],
            ['attribute'=>'student.bank.name','label'=>'Наименование<br>банка','encodeLabel' => false],
            ['attribute'=>'userFrom.username','label'=>'Первоначальная<br>организация','encodeLabel' => false,'value'=>function($model){
                if (isset($model->userFrom)) {
                    if (isset($model->student->organization))
                        return $model->student->organization->name . "(" . $model->userFrom->username . ")";
                    return  "Не известная организация(" . $model->userFrom->username . ")";
                }
               return '';
            }/*,'visible'=>!Yii::$app->user->can('podved')*/],
            ['attribute'=>'userTo.username','label'=>'Конечная<br>организация','encodeLabel' => false,'value'=>function($model){
                if (isset($model->userTo)) {
                    if (isset($model->userTo->organization))
                        return $model->userTo->organization->name . "(" . $model->userTo->username . ")";
                    return  "Не известная организация(" . $model->userTo->username . ")";
                }
                return '';
            }],
            'changes:ntext',

            [
                    'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{add}',
                'buttons'=>[
                        'add'=>function ($url, $model, $key) {
                            return "<a href='$url' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-plus'></span></a>";
                        },
                        'view'=>function ($url, $model, $key) {
                            $u = Url::to(['app/students/view','id'=>$model->student->id]);
                            return "<a href='$u' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        }
                ],
                'visibleButtons'=>[
                        'add'=>
                            function ($model, $key, $index) {
                                return ($model->userTo) ? false : true;
                             }
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

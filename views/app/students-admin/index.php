<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StudentsAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students Admins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Students Admin', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div style="overflow-x: auto">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                [
                    'attribute'=>'org_name',
                    'value'=>'org.name',
                    'label'=>'Название организации'
                ],
                'date_credit',
                'date_start:date',
                'code',
                [
                    'attribute'=>'education_status',
                    'value'=>function($model){return $model->education_status ? 'Обучается' : 'Не обучается'; },
                    'filter'=>[0=>'Не обучается',1=>'Обучается']
                ],
                [
                    'attribute'=>'status',
                    'value'=>function($model){return $model->status == 2 ? 'Утвержден' : 'Не утвержден'; },
                    'filter'=>[1=>'Не утвержден',2=>'Утвержден']
                ],
                [
                    'attribute'=>'osnovanie',
                    'value'=>function($model){return \app\models\app\students\Students::getOsnovanie()[$model->osnovanie ?? 0]; },
                    'filter'=>[
                        1=>'отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по профессиональной образовательной программе обязанностей по добросовестному освоению такой образовательной программы и выполнению учебного плана',
                        2=>'установление нарушения порядка приема в образовательную организацию, повлекшего по вине обучающегося его незаконное зачисление в образовательную организацию',
                        3=>'отчислен по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося',
                        4=>'в связи с ликвидацией образовательной организации',
                        5=>'по независящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации',
                        6=>'обучающимся (заемщиком) принято решение об отказе от продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации, в том числе в случае ликвидации образовательной организации'
                    ]
                ],
                [
                    'attribute'=>'grace_period',
                    'value'=>function($model){return \app\models\app\students\Students::getGracePeriod()[$model->grace_period ?? 0]; },
                    'filter'=>[
                        1=>'академический отпуск',
                        2=>'отпуск по беременности и родам',
                        3=>'отпуск по уходу за ребенком по достижении им 3-х лет',
                        ]
                ],
                [
                    'attribute'=>'id_number_pp',
                    'value'=>'numberPp.number',
                    'filter'=>[
                        1=>197,
                        2=>1026,
                        3=>699,
                    ]
                ],
                [
                    'attribute'=>'id_bank',
                    'value'=>'bank.name',
                    'filter'=>[
                        1=>'ПАО "Сбербанк России"',
                        2=>'Банк Союз(АО)',
                        3=>'ВТБ ПАО',
                        4=>'Промсвязьбанк'
                    ]
                ],
                [
                    'attribute'=>'isEnder',
                    'value'=>function($model){return $model->isEnder? 'Выпускник' : 'Не Выпускник'; },
                    'filter'=>[1=>'Выпускник',0=>'Не Выпускник']
                ],
                [
                    'attribute'=>'perevod',
                    'value'=>function($model){return $model->perevod? 'Переведен' : 'Не переведен'; },
                    'filter'=>[1=>'Выпускник',0=>'Не Выпускник']
                ],
                [
                    'attribute'=>'system_status',
                    'value'=>function($model){return $model->system_status? 'Живой' : 'Удален'; },
                    'filter'=>[1=>'Живой',0=>'Удален']
                ],
                'old_code',
                'id_org_old',
            ],
            'rowOptions'=>function($model)
            {
                $url = Url::to(['view','id'=>$model->id]);
                return [
                    'onClick'=>"window.location.href='{$url}'",
                    'style'=>'cursor:pointer',
                    'class'=>'toVisible'
                ];
            },
        ]); ?>
    </div>


    <?php Pjax::end(); ?>

</div>

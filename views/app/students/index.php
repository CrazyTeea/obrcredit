<?php

use app\models\app\students\Students;
use kartik\export\ExportMenu;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportProvider yii\data\ActiveDataProvider */

$months = [
  'Январь',
  'Февраль',
  "Март",
  "Апрель",
  "Май",
  "Июнь",
  "Июль",
  "Август",
  "Сентябрь",
  "Октябрь",
  "Ноябрь",
  "Декабрь"
];

$cans = Yii::$app->session->get('cans');
$year = Yii::$app->session->get('year');
$month = Yii::$app->session->get('month');
$month_m = $months[Yii::$app->session->get('month')-1];
$bank = Yii::$app->session->get('id_bank');
$npp = \app\models\app\students\NumbersPp::findOne(Yii::$app->session->get('nPP'))->number;
$this->title = "Обучающиеся: ".Yii::$app->session['short_name_org'];

$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($year and $bank){
    $this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['app/main']];
    $this->params['breadcrumbs'][] = ['label'=>'Выбор месяца','url'=>['app/main/month','year'=>$year]];
}

if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label'=>'Организации','url'=>['app/organizations/by-bank','id_bank'=>Yii::$app->session['id_bank'],'month'=>Yii::$app->session['month'],'nPP'=>Yii::$app->session['nPP']]];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="students-index">
    <!-- from gitlab -->

    <h3><?= Html::encode($this->title) ?></h3>
    <h4><span class="label label-info"><?=" Год: $year Месяц: $month_m номер ПП: $npp"?></span></h4>

    <?php  if ($cans[0] || $cans[1]):?>
        <?= Html::a('Добавить студента', ['create','id'=>Yii::$app->session[ 'id_org' ]],['class'=>'btn btn-success']) ?>
    <?php endif;?>

    <?= ExportMenu::widget(
        [
            'dataProvider'=>$exportProvider,
            'columns' => Students::getColumns(true)
            ,'batchSize'=>10,'target'=>'_blank'
        ]
    ) ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'options'=>['id'=>'PrintThis'],
        'columns' => Students::getColumns(false),
        'rowOptions'=>function($model, $index, $attribute)
        {
            $url = Url::to(['view','id'=>$model->id]);
            return [
                'onClick'=>"window.location.href='{$url}'",
                'style'=>'cursor:pointer',
                'class'=>'toVisible'
            ];
        },
    ]); ?>

    <?php if (!$isApprove):?>
        <div class="alert alert-warning">
            <p>
                Данные утверждены и не подлежат редактированию
            </p>
        </div>
    <?php else:?>
        <div class="raw">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
                <?= Html::a('Подтвердить за месяц',['approve'],['class'=>'btn btn-danger','data' => [
                    'confirm' => 'Вы уверены?',
                ],]) ?>
            </div>
        </div>
    <?php endif;?>

    <?php Pjax::end(); ?>



</div>

<?php

use app\models\app\students\Students;
use kartik\export\ExportMenu;
use yii\bootstrap\Collapse;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;


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

$exportProvider = $views['index']['export'];
?>
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
<div class="row">
    <div class="col-md-6"><?= Tabs::widget([
            'items'=>[
                [
                    'label'=>'Текущие',
                    'content'=>$this->render('_studentsView',compact('views','cans'))
                ],
                [
                    'label'=>'Отчисленные',
                    'content'=>$this->render('_otchView',compact('views','cans'))
                ],
                [
                    'label'=>'Выпускники',
                    'content'=>$this->render('_endView',compact('views','cans'))
                ],
                [
                    'label'=>'Не найденные',
                    'content'=>$this->render('_zhurnal',compact('views','cans'))
                ],
            ]
        ]) ?></div>

</div>




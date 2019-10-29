<?php

use app\models\app\students\Students;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportProvider yii\data\ActiveDataProvider */

$this->title = "Обучающиеся: ".Yii::$app->session['short_name_org'];
$cans = Yii::$app->session['cans'];
$year = Yii::$app->session['year'];
$bank = Yii::$app->session['bank'];
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($year and $bank){
    $this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['app/main']];
    $this->params['breadcrumbs'][] = ['label'=>'Выбор месяца','url'=>['app/main/month','year'=>$year]];
}

if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label'=>'Организации','url'=>['app/organizations']];
$this->params['breadcrumbs'][] = $this->title;

$isApprove = false;
    foreach ($dataProvider->getModels() as $student)
        if ($student->status != 2){
            $isApprove = true;
            break;
        }

?>
<div class="students-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php if ($cans[0] || $cans[1]):?>
        <?= Html::a('Добавить студента', ['create'],['class'=>'btn btn-success']) ?>
    <?php endif;?>

    <?= ExportMenu::widget(
        [
            'dataProvider'=>$exportProvider,
            'columns' => $exportColumns
        ]
    ) ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'options'=>['id'=>'PrintThis'],
        'columns' => $columns,
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


    <?php Pjax::end(); ?>
    <?php if (($cans[2] || $cans[0]) and $isApprove):?>
        <div class="raw">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
                <?= Html::a('Утвердить!',['approve'],['class'=>'btn btn-danger','data' => [
                    'confirm' => 'Вы уверены?',
                ],]) ?>
            </div>
        </div>
    <?php endif;?>
    <?php if (!$isApprove):?>
        <div class="alert alert-warning">
            <p>
                Данные утверждены и не подлежат редактированию
            </p>
        </div>
    <?php endif;?>


</div>

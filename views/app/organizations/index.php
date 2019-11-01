<?php

use app\models\app\students\Students;
use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\OrganizationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProviderStudent yii\data\ActiveDataProvider */

$this->title = 'Организации';
$year = Yii::$app->session['year'];
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($year){
    $this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['app/main']];
    $this->params['breadcrumbs'][] = ['label'=>'Выбор месяца','url'=>['app/main/month','year'=>$year]];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizations-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-3">
            <?= ExportMenu::widget(
                [
                        'pjaxContainerId'=>'ex',
                    'dataProvider'=>$dataProviderStudent,
                    'emptyCell'=>' ',
                    'columns' =>$exportColumns,
                ]) ?>
        </div>

        <?php $form = ActiveForm::begin(['method'=>'get'])?>
        <div class="col-md-3">
            <?=$form->field($searchModel,'isColored')->checkbox(); ?>

        </div>

        <div class="col-md-3">
            <?=Html::submitButton('Отфильровать(зеленые/красные)',['class'=>'btn btn-success'])?>
        </div>
        <?php  ActiveForm::end()?>
        <div class="col-md-3">
            <label for="kartik-export"> Выгрузка красных
            <?= ExportMenu::widget(
                [
                    'pjaxContainerId'=>'exded',
                    'dataProvider'=>$clrPr,
                    'emptyCell'=>' ',
                    'columns' =>['id','name'],
                ]) ?>
            </label>
        </div>
    </div>






    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyCell'=>' ',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'short_name',
            'full_name',
        ],
        'rowOptions'=>function($model, $index, $attribute) use ($searchModel) {
            $url = Url::to(['app/students/index','id'=>$model->id]);
            return [
                'onClick'=>"window.location.href='{$url}'",
                'style'=>'cursor:pointer',
                'class'=>'toVisible',
                'id'=> $searchModel->isColored ? $model->studentsCount  ? 'red' : 'green' : ''
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>

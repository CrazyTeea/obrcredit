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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizations-index">

    <h1><?= Html::encode($this->title) ?></h1>




    <div class="row">
        <div class="col-md-4">
            <?= ExportMenu::widget(
                [
                    'dataProvider'=>$dataProviderStudent,
                    'emptyCell'=>' ',
                    'columns' =>$exportColumns,
                ]) ?>
        </div>
        <?php $form = ActiveForm::begin(['method'=>'get'])?>
        <div class="col-md-4">
            <?=$form->field($searchModel,'isColored')->checkbox(); ?>
        </div>
        <div class="col-md-4">
            <?=Html::submitButton('Отфильровать(зеленые/красные)',['class'=>'btn btn-success'])?>
        </div>
        <?php  ActiveForm::end()?>
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

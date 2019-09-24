<?php

use app\models\app\students\Students;
use kartik\export\ExportMenu;
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

    <?= ExportMenu::widget(['dataProvider'=>$dataProviderStudent,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'education_status','content'=>function($model){
                return $model->education_status ? 'Обучается' : 'Не обучается';
            }],
            ['attribute'=>'organization.short_name','label'=>'Организация'],
            'name',
            'code',
            'date_create:date',
            'date_education_status:date',
            ['attribute'=>'osnovanie','content'=>function($model){
                return  Students::getOsnovanie()[$model->osnovanie ? $model->osnovanie : 0];
            }],
            ['attribute'=>'grace_period','content'=>function($model){
                return  Students::getGracePeriod()[$model->grace_period ? $model->grace_period : 0];
            }],
            ['attribute'=>'date_start_grace_period','value'=>function($model){
                return $model->date_start_grace_period and $model->date_end_grace_period ?
                        Yii::$app->getFormatter()->asDate($model->date_start_grace_period).'-'.
                        Yii::$app->getFormatter()->asDate($model->date_end_grace_period):'';
            }],
            ['attribute'=>'status','content'=>function($model){
                return  $model->status ? 'Действующий' : 'Не действующий';
            }],
        ],
    ]) ?>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'short_name',
            'full_name',
        ],
        'rowOptions'=>function($model, $index, $attribute)
        {
            $url = Url::to(['app/students/index','id'=>$model->id]);
            return [
                'onClick'=>"window.location.href='{$url}'",
                'style'=>'cursor:pointer',
                'class'=>'toVisible'
            ];
        },
    ]); ?>

    <?php Pjax::end(); ?>

</div>

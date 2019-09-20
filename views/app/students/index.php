<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Студенты';
$this->params['breadcrumbs'][] = ['label'=>'Организации','url'=>['app/organizations']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (User::$cans[0] || User::$cans[1]):?>

        <p>
            <?= Html::a('Добавить студента', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //   'id',
            'name',
            'organization.short_name',
            'code',
            ['attribute'=>'education_status','content'=>function($model){
                $val = $model->education_status ? 'Обучается' : 'Не обучается';
                return  "<span class='label label-info'> {$val}</span>";
            }],
            //'date_education_status',
            'date_create:date',
            ['attribute'=>'status','content'=>function($model){
                $val = $model->status ? 'Действующий' : 'Не действующий';
                return  "<span class='label label-info'> {$val}</span>";
            }],
            //'osnovanie',
            //'grace_period',
            //'date_start_grace_period',
            //'date_end_grace_period',

            //['class' => 'yii\grid\ActionColumn'],
        ],
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

</div>

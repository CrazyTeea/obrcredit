<?php

use app\models\app\students\Students;
use app\models\User;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Студенты: ".Yii::$app->session['short_name_org'];
if (User::$cans[0] || User::$cans[1])
    $this->params['breadcrumbs'][] = ['label'=>'Организации','url'=>['app/organizations']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php if (User::$cans[0] || User::$cans[1]):?>

        <button class="btn btn-success">
            <?= Html::a('Добавить студента', ['create']) ?>
        </button>
    <?php endif;?>


        <?= ExportMenu::widget(['dataProvider'=>$dataProvider,
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
                ['attribute'=>'date_start_grace_period','label'=>'Период','value'=>function($model){
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

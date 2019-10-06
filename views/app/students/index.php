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

$this->title = "Обучающиеся: ".Yii::$app->session['short_name_org'];
$cans = Yii::$app->session['cans'];
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

    <?= ExportMenu::widget(['dataProvider'=>$dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'№ п/п'],
            ['attribute'=>'name','header'=>'ФИО обучающегося'],
            ['attribute'=>'organization.short_name','header'=>'Наименование ООВО'],
            ['attribute'=>'code','header'=>'Код направления подготовки'],
            ['attribute'=>'education_status','header'=>'Статус обучающегося','content'=>function($model){
                return $model->education_status ? "Обучается" :" Не обучается";
            }],
            ['attribute'=>'dateLastStatus.date_end','format'=>'date','header'=>'Дата отчисления'],
            ['attribute'=>'grace_period','value'=>function($model){return Students::getGracePeriod()[$model->grace_period ? $model->grace_period : 0];}
                ,'header'=>'Отсрочка льготного периода'
            ],
            ['attribute'=>'date_start_grace_period1','value'=>
                function($model){
                    if ($model->date_start_grace_period1 and $model->date_end_grace_period1 and $model->grace_period == 1)
                        return Yii::$app->getFormatter()->asDate($model->date_start_grace_period1).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period1);
                    if ($model->date_start_grace_period2 and $model->date_end_grace_period2 and $model->grace_period == 2)
                        return Yii::$app->getFormatter()->asDate($model->date_start_grace_period2).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period2);
                    if ($model->date_start_grace_period3 and $model->date_end_grace_period3 and $model->grace_period == 3)
                        return Yii::$app->getFormatter()->asDate($model->date_start_grace_period3).'-'.Yii::$app->getFormatter()->asDate($model->date_end_grace_period3);
                    return '';
                } ,
                'header'=>'Срок действия академического права',
            ],
            ['attribute'=>'date_credit','header'=>'Дата заключения кредитного договора',],
            ['attribute'=>'dateLastStatus.updated_at','header'=>'Дата изменения данных'],
            ['attribute'=>'numberPP.number','header'=>'Номер ПП по образовательному кредиту'],
            ['attribute'=>'bank.name','header'=>'Наименование банка или иной кредитной организации'],
            ['attribute'=>'date_status','format'=>'date','header'=>'Дата утрерждения отчета'],
        ],
    ]) ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div id="PrintThis">
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
    </div>

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

</div>

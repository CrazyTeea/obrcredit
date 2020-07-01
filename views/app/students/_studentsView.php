<?php
use app\models\app\students\Students;
use kartik\export\ExportMenu;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/***
 * @var $views array
 */


$dataProvider = $views['index']['provider'];
$searchModel = $views['index']['search'];
$isApprove = $views['index']['isApprove'];

?>
<div class="students-index">
    <br>





<?php Pjax::begin(['timeout'=>5000]); ?>
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
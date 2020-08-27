<?php
use app\models\app\students\Students;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/***
 * @var $views array
 */


$dataProvider = $views['A']['provider'];
$searchModel = $views['A']['search'];


?>
<div class="students-otch-index">
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

    <?php Pjax::end(); ?>

</div>
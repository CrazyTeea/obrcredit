<?php

use app\models\app\students\Students;
use app\models\app\students\StudentsHistorySearch;
use kartik\export\ExportMenu;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Журнал изменений по постановлению Правительства №'.\app\models\app\students\NumbersPp::findOne(Yii::$app->session->get('nPP'))->number;
$this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['app/main']];
$this->params['breadcrumbs'][] = ['label'=>Yii::$app->session->get('year').' год','url'=>['app/main/month','year'=>Yii::$app->session->get('year')]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="students-history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['enableReplaceState'=>false,'enablePushState'=>false]); ?>
    <?php //= $this->render('_search', ['model' => $searchModel,'changes'=>$changes]); ?>

    <?=ExportMenu::widget(['dataProvider'=>$dataProvider,'columns'=> StudentsHistorySearch::getColumns()]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => StudentsHistorySearch::getColumns(),
    ]); ?>

    <?php
    Modal::begin([
        'header'=>'<h4>Update Model</h4>',
        'id'=>'update-modal',
        'size'=>'modal-lg'
    ]);

    echo "<div id='updateModalContent'></div>";

    Modal::end();
    ?>

    <?php Pjax::end(); ?>

</div>

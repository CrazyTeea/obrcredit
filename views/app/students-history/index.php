<?php

use app\models\app\students\Students;
use app\models\app\students\StudentsHistorySearch;
use kartik\export\ExportMenu;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
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

    <?=Tabs::widget(['items'=>[
        [
            'label' => 'Журнал',
            'content' => $this->render('_zhurnal',compact('dataProvider','searchModel')),
            'active' => true // указывает на активность вкладки
        ],
        [
            'label' => 'Выпускники',
            'content' => $this->render('_ender',compact('dataProvider2','searchModelEnd')),
        ],
        [
            'label' => 'Заголовок вкладки 3',
            'content' => 'Вкладка 3',
            'headerOptions' => [
                'id' => 'someId'
            ]
        ],
    ]]) ?>



</div>

<?php

use app\models\app\students\Students;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\students\StudentsHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Students Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['enableReplaceState'=>false,'enablePushState'=>false]); ?>
   <!--<?/*= $this->render('_search', ['model' => $searchModel,'changes'=>$changes]); */?> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => \app\models\app\students\StudentsHistorySearch::getColumns(),
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\app\OplataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oplatas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplata-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Oplata', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'numberpp_id',
            'bank_id',
            'payment_date',
            'latter_number',
            //'latter_date',
            //'order_number',
            //'order_date',
            //'updated_at',
            //'created_at',
            //'system_status',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

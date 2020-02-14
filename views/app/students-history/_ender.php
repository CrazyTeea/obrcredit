<?php


use app\models\app\students\StudentsHistorySearch;
use kartik\export\ExportMenu;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    'code',
    'organization.name',
    'bank.name',
    'date_ender:date',
    ['value'=>function($model){
        if ($model->date_ender){
            $date = explode('-',$model->date_ender);
            $date[1]+=3;
            if ($date[1]>12){
                $date[1]-=12;
                $date[0]++;
            }
            $date[0]+=10;
            return implode('-',$date);
        }
    },'format'=>'date','encodeLabel' => false,'label'=>'Контроль окончания <br> срока кредитных <br> обязательств']
]


?>
    <h1><?= 'Выпускники' ?></h1>

<?php Pjax::begin(['enableReplaceState'=>false,'enablePushState'=>false,'timeout'=>5000]); ?>
<?php //= $this->render('_search', ['model' => $searchModel,'changes'=>$changes]); ?>

<?=ExportMenu::widget(['dataProvider'=>$dataProvider2,'columns'=>$columns ]) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider2,
    'filterModel' => $searchModelEnd,
    'columns' => $columns,
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
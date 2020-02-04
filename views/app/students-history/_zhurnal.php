<?php

use app\models\app\students\StudentsHistorySearch;
use kartik\export\ExportMenu;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;


?>
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
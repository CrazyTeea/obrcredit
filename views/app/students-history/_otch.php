<?php


use kartik\export\ExportMenu;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    'code',
    'organization.name',
    'bank.name'
]

?>
    <h1><?= 'Отчисленные' ?></h1>

<?php Pjax::begin(['enableReplaceState'=>false,'enablePushState'=>false,'timeout'=>5000]); ?>
<?php //= $this->render('_search', ['model' => $searchModel,'changes'=>$changes]); ?>

<?=ExportMenu::widget(['dataProvider'=>$dataProvider3,'columns'=>$columns ]) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider3,
    'filterModel' => $searchModelOtch,
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
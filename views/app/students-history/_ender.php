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
    'bank.name'
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
<?php


use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    'code',
    'organization.name',
    'bank.name',
    [
            'class'=>'yii\grid\ActionColumn',
        'template'=>'{view}{add}',
        'buttons'=>[
            'add'=>function ($url, $model, $key) {
                $btn = "<a href='$url' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-plus'></span></a>";


                    $btn = "
<!-- Button trigger modal -->
    <a  class='glyphicon glyphicon-plus' data-toggle='modal' data-target='#myModal_$model->id' style='margin-bottom: 5px'>
    </a>

    <!-- Modal -->
    <div class='modal fade' id='myModal_$model->id' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
            <form method='post' action='/app/students/return?id={$model->id}'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    <h4 class='modal-title' id='myModalLabel'>Отправить в журнал</h4>
                </div>
                <div class='modal-body'>"
                        .Html::hiddenInput(Yii::$app->request->csrfParam,Yii::$app->request->getCsrfToken())
                        .Html::input('date','Students[date_start]',null,['class'=>'form-control']).
                        "
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Закрыть</button>
                    <button type='submit' class='btn btn-primary'>Отправить</button>
                </div>
            </form>
            </div>
        </div>
    </div>
";
                return $btn;
            },
            'view'=>function ($url, $model, $key) {
                $u = Url::to(['app/students/view','id'=>$model->id]);
                return "<a href='$u' aria-label='Скрыть' data-pjax='0'><span class='glyphicon glyphicon-eye-open'></span></a>";
            }
        ],
    ]
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
<?php

use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Имопрт студентов';
$this->params['breadcrumbs'][] = $this->title;

?>


    <?= FileInput::widget([
    'model'=>$model,
    'attribute'=>'_csv',
    'pluginOptions' => [
        'uploadUrl'=>\yii\helpers\Url::to(['app/import']),
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => false,
        'showUpload' => true
    ],
    'options' => ['multiple' => false,'id'=>'csv_input']]) ?>


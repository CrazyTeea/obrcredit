<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="students-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_org') ->widget(Select2::className(),['data'=>$orgs])?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'education_status')->textInput() ?>

    <?= $form->field($model, 'date_education_status')->Input('date') ?>

    <?= $form->field($model, 'date_create')->Input('date') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'osnovanie')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

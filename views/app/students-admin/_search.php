<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StudentsAdminSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="students-admin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'id_org') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'education_status') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'osnovanie') ?>

    <?php // echo $form->field($model, 'grace_period') ?>

    <?php // echo $form->field($model, 'date_start_grace_period3') ?>

    <?php // echo $form->field($model, 'date_end_grace_period3') ?>

    <?php // echo $form->field($model, 'date_credit') ?>

    <?php // echo $form->field($model, 'id_number_pp') ?>

    <?php // echo $form->field($model, 'id_bank') ?>

    <?php // echo $form->field($model, 'date_status') ?>

    <?php // echo $form->field($model, 'date_start_grace_period1') ?>

    <?php // echo $form->field($model, 'date_end_grace_period1') ?>

    <?php // echo $form->field($model, 'date_start_grace_period2') ?>

    <?php // echo $form->field($model, 'date_end_grace_period2') ?>

    <?php // echo $form->field($model, 'date_start') ?>

    <?php // echo $form->field($model, 'perevod') ?>

    <?php // echo $form->field($model, 'isEnder') ?>

    <?php // echo $form->field($model, 'date_ender') ?>

    <?php // echo $form->field($model, 'system_status') ?>

    <?php // echo $form->field($model, 'old_code') ?>

    <?php // echo $form->field($model, 'id_org_old') ?>

    <?php // echo $form->field($model, 'date_act') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\StudentsHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="students-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_student')->textInput() ?>

    <?= $form->field($model, 'id_user_from')->textInput() ?>

    <?= $form->field($model, 'changes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'system_status')->textInput() ?>

    <?= $form->field($model, 'id_user_to')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

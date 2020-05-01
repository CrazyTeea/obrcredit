<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StudentsAdmin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="students-admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_org')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'education_status')->dropDownList([1=>'Обучается',0=>'Не обучается']) ?>

    <?= $form->field($model, 'status')->dropDownList([2=>'Утвержден',1=>'Не Утвержден']) ?>

    <?= $form->field($model, 'osnovanie')->dropDownList([
            0=>null,
        1=>'отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по профессиональной образовательной программе обязанностей по добросовестному освоению такой образовательной программы и выполнению учебного плана',
        2=>'установление нарушения порядка приема в образовательную организацию, повлекшего по вине обучающегося его незаконное зачисление в образовательную организацию',
        3=>'отчислен по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося',
        4=>'в связи с ликвидацией образовательной организации',
        5=>'по независящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации',
        6=>'обучающимся (заемщиком) принято решение об отказе от продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации, в том числе в случае ликвидации образовательной организации'
    ]) ?>

    <?= $form->field($model, 'grace_period')->dropDownList([
        0=>null,
        1=>'академический отпуск',
        2=>'отпуск по беременности и родам',
        3=>'отпуск по уходу за ребенком по достижении им 3-х лет',
    ]) ?>

    <?= $form->field($model, 'date_start_grace_period3')->input('date') ?>

    <?= $form->field($model, 'date_end_grace_period3')->input('date') ?>

    <?= $form->field($model, 'date_credit')->textInput() ?>

    <?= $form->field($model, 'id_number_pp')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\app\students\NumbersPp::find()->select(['id','number'])->all(),'id','number')) ?>

    <?= $form->field($model, 'id_bank')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\app\Banks::find()->select(['id','name'])->all(),'id','name')) ?>


    <?= $form->field($model, 'date_start_grace_period1')->input('date') ?>

    <?= $form->field($model, 'date_end_grace_period1')->input('date') ?>

    <?= $form->field($model, 'date_start_grace_period2')->input('date') ?>

    <?= $form->field($model, 'date_end_grace_period2')->input('date') ?>

    <?= $form->field($model, 'date_start')->input('date') ?>

    <?= $form->field($model, 'perevod')->checkbox() ?>

    <?= $form->field($model, 'isEnder')->checkbox() ?>

    <?= $form->field($model, 'date_ender')->input('date') ?>

    <?= $form->field($model, 'system_status')->checkbox() ?>

    <?= $form->field($model, 'old_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_org_old')->textInput() ?>

    <?= $form->field($model, 'date_act')->input('date') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

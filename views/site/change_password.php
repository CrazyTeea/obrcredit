<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

$this->title = 'Смена пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-change_password">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Смена пароля:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php Pjax::begin(['id'=>'form_ch_psw'])?>
            <?php $form = ActiveForm::begin(['id' => 'form-change_password','options' => ['data-pjax' => true]]); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model,'password')?>
            <div class="form-group">
                <?= Html::submitButton('Сменить', ['class' => 'btn btn-primary', 'name' => 'change_password-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php switch ($success){
                case 0:{
                    echo '<div class="alert alert-warning">Что-то пошло не так или такого пользователя не существует !</div>';
                    break;
                }
                case 1:{
                    echo '<div class="alert alert-success">Успешно!</div>';
                    break;
                }
                default:{
                    break;
                }
            }?>

            <?php Pjax::end()?>

        </div>
    </div>
</div>
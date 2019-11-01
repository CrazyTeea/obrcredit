<?php

use app\models\app\Organizations;
use app\models\app\students\Students;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Новый пользователь';
$this->params['breadcrumbs'][] = $this->title;

$form =  ActiveForm::begin();

?>

<?= $form->field($new_user,'username') ->textInput()?>
<?= $form->field($new_user,'name')->textInput()?>
<?= $form->field($new_user,'password')->textInput() ?>
<?= $form->field($new_user,'id_org')->widget(Select2::className(),['data'=>Organizations::getOrgs()])?>

<?= Html::submitButton('Добавить',['class'=>'btn btn-primary'])?>

<?php ActiveForm::end(); ?>

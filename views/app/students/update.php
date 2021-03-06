<?php

use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */

$this->title = 'Редактирование студента: ' . $model->name;
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$cans = Yii::$app->session['cans'];
if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label' => 'Организация', 'url' => ['app/organizations/index']];
$this->params['breadcrumbs'][] = ['label' => 'Обучающиеся', 'url' => ['index','id'=>$model->id_org]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="students-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form',compact('model','orgs','file','docTypes')) ?>

</div>

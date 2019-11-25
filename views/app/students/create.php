<?php

use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */

$this->title = 'Добавление студента';
$cans = Yii::$app->session['cans'];
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label' => 'Организация', 'url' => ['app/organizations/index']];

$this->params['breadcrumbs'][] = ['label' => 'Обучающиеся', 'url' => ['index','id'=>Yii::$app->session['id_org']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form',compact('model','orgs','file','docTypes')) ?>

</div>

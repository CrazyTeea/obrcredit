<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\NumbersPp */

$this->title = 'Update Numbers Pp: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Numbers Pps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="numbers-pp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

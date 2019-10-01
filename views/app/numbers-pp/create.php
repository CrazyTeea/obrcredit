<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\NumbersPp */

$this->title = 'Create Numbers Pp';
$this->params['breadcrumbs'][] = ['label' => 'Numbers Pps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="numbers-pp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

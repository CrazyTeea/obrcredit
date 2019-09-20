<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\Organizations */

$this->title = 'Create Organizations';
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

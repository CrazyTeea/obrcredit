<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\Oplata */

$this->title = 'Create Oplata';
$this->params['breadcrumbs'][] = ['label' => 'Oplatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplata-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\StudentsHistory */

$this->title = 'Create Students History';
$this->params['breadcrumbs'][] = ['label' => 'Students Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

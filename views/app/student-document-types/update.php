<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\StudentDocumentTypes */

$this->title = 'Update Student Document Types: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Student Document Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-document-types-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

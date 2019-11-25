<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\StudentDocumentList */

$this->title = 'Create Student Document List';
$this->params['breadcrumbs'][] = ['label' => 'Student Document Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-document-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

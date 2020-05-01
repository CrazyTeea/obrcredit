<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentsAdmin */

$this->title = 'Update Students Admin: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Students Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="students-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

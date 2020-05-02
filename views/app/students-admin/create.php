<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentsAdmin */

$this->title = 'Добавление студента';
$this->params['breadcrumbs'][] = ['label' => 'Students Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="students-admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

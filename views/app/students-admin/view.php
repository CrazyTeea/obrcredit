<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StudentsAdmin */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Students Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="students-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить только этого', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверен?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Удалить этого и всех клонов', ['delete-all', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверен?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Удалить логически только этого', ['delete-logic', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверен?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Удалить логически этого и всех клонов', ['delete-logic-all', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверен?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Удалить из журнала клонов', ['delete-zhurnal-all', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Уверен?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Восстановить только этого', ['recover', 'id' => $model->id], [
            'class' => 'btn btn-info'
        ]) ?>
        <?= Html::a('Восстановить этого и всех клонов', ['recover-all', 'id' => $model->id], [
            'class' => 'btn btn-info',
        ]) ?>
        <?= Html::a('Отправить в журнал', ['add-history', 'id' => $model->id], [
            'class' => 'btn btn-danger',
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'id_org',
            'org.name',
            'code',
            [
                'attribute'=>'education_status',
                'value'=>function($model){return $model->education_status?'Обучается':'Не обучается';}
            ],
            'date_create:date',
            [
                'attribute'=>'status',
                'value'=>function($model){return $model->status == 2?'Утвержден':'Не утвержден';}
            ],
            [
                'attribute'=>'osnovanie',
                'value'=>function($model){return \app\models\app\students\Students::getOsnovanie()[$model->osnovanie ?? 0];}
            ],
            [
                'attribute'=>'grace_period',
                'value'=>function($model){return \app\models\app\students\Students::getGracePeriod()[$model->grace_period ?? 0];}
            ],
            'date_start_grace_period3:date',
            'date_end_grace_period3:date',
            'date_credit',
            'numberPp.number',
            'bank.name',
            'date_status:date',
            'date_start_grace_period1:date',
            'date_end_grace_period1:date',
            'date_start_grace_period2:date',
            'date_end_grace_period2:date',
            'date_start:date',
            [
                'attribute'=>'perevod',
                'value'=>function($model){return $model->perevod?'Переведен':'Не переведен';}
            ],
            [
                'attribute'=>'isEnder',
                'value'=>function($model){return $model->isEnder?'Выпускник':'Не выпускник';}
            ],
            'date_ender:date',
            [
                'attribute'=>'system_status',
                'value'=>function($model){return $model->system_status?'Живой':'Удален';}
            ],
            'old_code',
            'id_org_old',
            'date_act',
        ],
    ]) ?>

</div>

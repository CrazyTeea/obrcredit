<?php

use yii\grid\GridView;

$this->title = "Список организаций";
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo GridView::widget(
    ['dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'columns'=>[
            ['class'=>'yii\grid\SerialColumn'],
            'id',
            'name',
            'usersCount',
            ['attribute'=>'usersCount','value'=>function($model){
                if (!$model->usersCount)
                    return 'Не подключен';
                return 'Подключен';
            },'label'=>'Статус'],
            ['content'=>function($model){
                return \yii\helpers\Html::a('Файлы',"https://xn--80apneeq.xn--p1ai/index.php?option=service_fileOnlyIsmon&id_rep=691&id_podved={$model->id}&modul=service_reportPodved",['target'=>'_blank']);
            }]
        ]]
);

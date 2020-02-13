<?php
$this->title = 'Отключенные организации';
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params[ 'breadcrumbs' ][] = $this->title ;

use yii\helpers\Url;
use yii\widgets\Pjax; ?>
<?php Pjax::begin(['timeout'=>5000]); ?>
<?=\yii\grid\GridView::widget(['dataProvider'=>$dataProvider,'filterModel'=>$search,'columns'=>[
    ['class'=>\yii\grid\SerialColumn::className()],
    'id',
    'full_name',
    'name',
    'short_name'
],
]);
Pjax::end();
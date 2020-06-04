<?php
$months = [
    'Январь',
    'Февраль',
    "Март",
    "Апрель",
    "Май",
    "Июнь",
    "Июль",
    "Август",
    "Сентябрь",
    "Октябрь",
    "Ноябрь",
    "Декабрь"
];

$cans = Yii::$app->session->get('cans');
$year = Yii::$app->session->get('year');
$month = Yii::$app->session->get('month');
$month_m = $months[Yii::$app->session->get('month')-1];
$bank = Yii::$app->session->get('id_bank');
$npp = \app\models\app\students\NumbersPp::findOne(Yii::$app->session->get('nPP'))->number;
$this->title = 'История';

$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($year and $bank){
    $this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['app/main']];
    $this->params['breadcrumbs'][] = ['label'=>'Выбор месяца','url'=>['app/main/month','year'=>$year]];
}

if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label'=>'Организации','url'=>['app/organizations/by-bank','id_bank'=>Yii::$app->session['id_bank'],'month'=>Yii::$app->session['month'],'nPP'=>Yii::$app->session['nPP']]];
$this->params['breadcrumbs'][] = "Обучающиеся: ".Yii::$app->session['short_name_org'];
$this->params['breadcrumbs'][] = $this->title;

?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Имя</th>
            <th>Организация</th>
            <th>Обучается</th>
            <th>Обоснование</th>
            <th>Выпускник</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
    <tr style="cursor: grab" onclick="window.location.href = '<?=\yii\helpers\Url::toRoute(['view','id'=>$model->id])?>'">
        <td><?= Yii::$app->getFormatter()->asDate($model->date_start)?></td>
        <td><?=$model->name?></td>
        <td><?=$model->organization->name?></td>
        <td><?=$model->education_status ? 'Обучается': 'Не обучается'?></td>
        <td><?= \app\models\app\students\Students::getOsnovanie()[ $model->osnovanie ?? 0]?></td>
        <td><?=$model->isEnder ? 'Выпускник': 'Не выпускник'?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

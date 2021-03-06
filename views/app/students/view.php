<?php


use app\models\app\students\Students;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\YiiAsset;

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

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */

$this->title = $model->name;
$cans = Yii::$app->session->get('cans');
$month = Yii::$app->session->get('month');
$year = Yii::$app->session->get('year');
$bank = Yii::$app->session->get('id_bank');
$this->params['breadcrumbs'][] = ['label' => 'ОбрКредит', 'url' => ['/']];
if ($year and $bank) {
    $this->params['breadcrumbs'][] = ['label' => 'Выбор года', 'url' => ['app/main']];
    $this->params['breadcrumbs'][] = ['label' => 'Выбор месяца', 'url' => ['app/main/month', 'year' => $year]];
}

if ($cans[0] || $cans[1]) {
    $this->params['breadcrumbs'][] = ['label' => 'Организация', 'url' => ['app/organizations/by-bank', 'id_bank' => $bank, 'month' => $month, 'nPP' => Yii::$app->session['nPP']]];
    $this->params['breadcrumbs'][] = ['label' => 'Обучающиеся', 'url' => ['index', 'id' => $model->id_org]];
} else
    $this->params['breadcrumbs'][] = ['label' => 'Обучающиеся', 'url' => ['by-bank', 'id' => $model->id_bank, 'nPP' => $model->id_number_pp, 'month' => $month]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$files = [];
$canUpdate = (!$cans[2] || $model->status != 2) ? 1 : 0;
if (isset($model->docs)) {
    foreach ($model->docs as $doc) {
        if (isset($doc->type)) {
            $delete_link = Html::a('', ['delete-doc', 'id' => $model->id, 'desc' => $doc->type->descriptor], ['class' => 'glyphicon glyphicon-remove']);
            if (isset($doc->file)) {
                $files[$doc->type->descriptor] =
                    Html::a($doc->file->name, $doc->file->generateLink($model->id_org, $model->id)) . '<br>' .
                    $s = ($canUpdate) ? $delete_link : '';
            }
        }
    }
}
foreach ($docTypes as $docType) {
    if (!\yii\helpers\ArrayHelper::keyExists($docType->descriptor, $files)) {
        $files[$docType->descriptor] = 'Файл не загружен';
    }
}

/*$rasp_act0 = StudentDocs::getDocByDescriptorName('rasp_act0',$model->id);
$rasp_act1 = StudentDocs::getDocByDescriptorName('rasp_act1',$model->id);
$rasp_act2 = StudentDocs::getDocByDescriptorName('rasp_act2',$model->id);
$rasp_act3 = StudentDocs::getDocByDescriptorName('rasp_act3',$model->id);
$rasp_act4 = StudentDocs::getDocByDescriptorName('rasp_act4',$model->id);
$dogovor = StudentDocs::getDocByDescriptorName('dogovor',$model->id);
$rasp_act_otch = StudentDocs::getDocByDescriptorName('rasp_act_otch',$model->id);*/
//var_dump($model->dateLastStatus->updated_at);

?>

<div class="students-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->getFlash('history')): ?>
        <div class="alert alert-success">
            <p>
                <?= Yii::$app->session->getFlash('history', null, true) ?>
            </p>
        </div>
    <?php endif; ?>


    <h4>
        <span class="label label-info"><?= " Год: $year Месяц: " . $months[date('m', strtotime($model->date_start)) - 1] . " номер ПП: {$model->numberPP->number}" ?></span>
    </h4>
    <?php if ($model->id_org == 325): ?>
        <h4>
            <span class="label label-success"> Дата рождения: <?= $model->birthday ? Yii::$app->getFormatter()->asDate(strtotime($model->birthday), 'php: d F Y') : '' ?></span>
        </h4>
    <?php endif; ?>

    <?php if ($canUpdate): ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php endif; ?>
    <?= Html::a('Абитуриент', ['ab', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Досрочно погасил кредит', ['dp', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php if ($cans[0] or $cans[1]): ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить студента?',
                'method' => 'post',
            ],
        ]) ?>

    <?php endif; ?>

    <?php if (!$cans[2] and !$is_in_history): ?>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"
                style="margin-bottom: 5px">
            Не найден
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Отправить в журнал</h4>
                    </div>
                    <div class="modal-body">

                        <?= $form->field($history, 'id_change')->dropDownList($changes) ?>
                        <?= $form->field($history, 'comment') ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
    <?php if ($cans[0] or $cans[1]): ?>

        <div class="btn-group">
            <?= Html::a('отчисленным', ['otch', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Выпускником', ['vip', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Не найденным', ['not-found', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Активным', ['active', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>

    <?php endif; ?>
    <?= Html::a('Вернуться к списку', $route, ['class' => 'btn btn-default']); ?>
    <?= Html::a('История', ['history', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>


    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4"><p class="text-center ">Статус обучающегося</p></th>
            <th colspan="4"><p class="text-center">Пролонгация льготного периода пользования <br> образовательным
                    кредитом</p></th>
            <th><p class="text-center">Выпускник (завершено обучение в образовательной организации)</p></th>
        </tr>
        <tr>
            <td rowspan='2'><p class="text-sm-center">Продолжает <br> обучаться </p></td>
            <td colspan="3"><p class="text-sm-center">Досрочно прекратил образовательные отношения </p></td>
            <td rowspan='2'><p class="text-sm-center">Отсрочка льготного периода <br> в связи с предоставлением
                    академического права <br>
                    (пункт 12 часть 1 статья 34 Федерального закона № 273-ФЗ) </p>
            </td>
            <td rowspan='2'><p class="text-sm-center">Срок действия академического права</p></td>
            <td rowspan='2'><p class="text-sm-center">Подтверждение
                    срока окончания академического права
                </p></td>
            <td rowspan="2"><p class="text-sm-center">Переведен на бюджет</p></td>
            <td rowspan="3"></td>
        </tr>
        <tr>
            <td>Пункты постановления <br> Правительства РФ <br> от 26.02.2018 г. № 197</td>
            <td>Основание</td>
            <td>Подтверждение основания</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td rowspan="6">
                <?= $model->education_status ? 'Обучается' : 'Не обучается' ?> <br>
                <?php //= ($model->dateLastStatus and $model->dateLastStatus->updated_at) ? Yii::$app->getFormatter()->asDate(strtotime($model->dateLastStatus->updated_at)) : date('d-M-Y') ?>
            </td>
            <td rowspan="3"><p class="text-sm-center"> пункт 20 </p></td>
            <td rowspan="2">
                <p class="text-sm-center">пункт 2 части 2 статьи 61 Федерального закона
                    № 273-ФЗ
                    <?php for ($i = 1; $i < 3; $i++) {
                        $k = Students::getOsnovanie()[$i];
                        if ($i == $model->osnovanie)
                            echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                        else echo "<p class='text-sm-center'> &#9744; $k</p>";
                    } ?>
                </p>
            </td>
            <td rowspan="3">
                <?= $files['rasp_act0'] ?>
            </td>
            <td rowspan="3">
                <?php
                $k = Students::getGracePeriod()[1];
                if (1 == $model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td rowspan="3" style="text-align: center; vertical-align: middle;"><p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period1) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period1) ?>
                </p>
            </td>
            <td rowspan="3">
                <?= $files['rasp_act1'] ?>
            </td>
            <td rowspan="6">
                <?php
                if ($model->perevod)
                    echo "<p class='text-sm-center' > &#9745; Переведен </p>";
                else echo "<p class='text-sm-center'> &#9744; Переведен</p>";
                ?>
                <?= $files['rasp_act4'] ?>
            </td>
            <td rowspan="6" style="text-align: center;">
                <?php
                if ($model->isEnder)
                    echo "<p class='text-sm-center' > &#9745; Выпускник </p>";
                else echo "<p class='text-sm-center'> &#9744; Выпускник</p>";
                ?>
                <?= Yii::$app->formatter->asDate($model->date_ender) ?>
                <?= $files['ender'] ?>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>
                <?php
                echo 'пункт 1 части 2 статьи 61 Федерального закона № 273-ФЗ <br><br>';
                $k = Students::getOsnovanie()[3];
                if (3 == $model->osnovanie)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 21 </p></td>
            <td><p class="text-sm-center">
                    перевод обучающегося для продолжения освоения основной профессиональной образовательной программы в
                    другую образовательную организацию:
                    <?php for ($i = 4; $i < 6; $i++) {
                        $k = Students::getOsnovanie()[$i];
                        if ($i == $model->osnovanie)
                            echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                        else echo "<p class='text-sm-center'> &#9744; $k</p>";
                    } ?>
                </p></td>
            <td>
                <?= $files['dogovor'] ?>
            </td>
            <td>
                <?php
                $k = Students::getGracePeriod()[2];
                if (2 == $model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td style="text-align: center; vertical-align: middle;"><p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period2) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period2) ?>
                </p>
            </td>
            <td>
                <?= $files['rasp_act2'] ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 22 </p></td>
            <td>
                <?php
                $k = Students::getOsnovanie()[6];
                if (6 == $model->osnovanie)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td>
                <?= $files['rasp_act_otch'] ?>
            </td>
            <td>
                <?php
                $k = Students::getGracePeriod()[3];
                if (3 == $model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period3) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period3) ?>
                </p>
            </td>
            <td>
                <?= $files['rasp_act3'] ?>
            </td>
        </tr>

        <!--<tr>

            <td><p class="text-sm-center"> пункт 35 </p></td>
            <td>
                <?php
        /*                $k = Students::getOsnovanie()[7];
                        if (7 == $model->osnovanie)
                            echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                        else echo "<p class='text-sm-center'> &#9744; $k</p>";
                        */ ?>
            </td>
            <td colspan="4"></td>
        </tr>-->

        </tbody>
    </table>


</div>

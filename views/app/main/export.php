<?php

$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['index']];
$this->title = 'Выгрузка';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
<?= \yii\bootstrap\Html::submitButton('Скачать',['class'=>'btn btn-default'])?>
<?php $form = \yii\bootstrap\ActiveForm::end() ?>
<br>

<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="3"></th>
        <th colspan="2">Январь</th>
        <th colspan="2">Февраль</th>
        <th colspan="2">Март</th>
        <th colspan="2">Апрель</th>
        <th colspan="2">Май</th>
        <th colspan="2">Июнь</th>
        <th colspan="2">Июль</th>
        <th colspan="2">Август</th>
        <th colspan="2">Сентябрь</th>
        <th colspan="2">Октябрь</th>
        <th colspan="2">Ноябрь</th>
        <th colspan="2">Декабрь</th>
    </tr>
    </thead>
    <tbody>


    <?php foreach ($student as $i=>$bank):?>
        <tr>
            <td class="tg-0lax" colspan="27"><?=$bank['name']?></td>
        </tr>
        <?php if (is_string($bank))continue;?>

        <?php foreach($bank as $num):?>
            <?php if (is_string($num))continue;?>

            <tr>
                <td class="tg-0lax" colspan="3">ПОСТАНОВЛЕНИЕ <?=$num['name']?></td>
            <?php foreach ($num as $month):?>
                <?php if (is_string($month))continue;?>


                <td class="tg-0lax"><?=$month['count']?></td>
                <td class="tg-0lax"></td>

            <?php endforeach;?>
            </tr>

            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Обучается</td>
            <?php foreach ($num as $month):?>
                <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['countO']?></td>
                    <td class="tg-0lax"></td>

            <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">п. 20 пункт 2 части 2 статьи 61 Федерального закона № 273-ФЗ, а<br>  также по инициативе обучающегося или родители</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по<br>  профессиональной образовательной программе обязанностей по добросовестному<br>  освоению такой образовательной программы и выполнению учебного плана</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count20_1']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">установление нарушения порядка приема в образовательную организацию, повлекшего по вине<br>  обучающегося его незаконное зачисление в образовательную организацию</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count20_2']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">отчислен по инициативе обучающегося или родителей (законных представителей)<br>  несовершеннолетнего обучающегося</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count20_3']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">п. 21 перевод обучающегося для продолжения освоения <br>основной профессиональной образовательной программы в другую<br>образовательную организацию:</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">в связи с ликвидацией образовательной организации</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count21_1']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">по независящим от воли обучающегося или родителей (законных представителей) <br>несовершеннолетнего обучающегося и образовательной организации</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count21_2']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">пункт 22 обучающимся (заемщиком) принято решение об отказе от<br>продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося<br>или родителей (законных представителей) несовершеннолетнего обучающегося и<br>образовательной организации, в том числе в случае ликвидации образовательной организации</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['count22']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">пункт 12 часть 1 статья 34 Федерального закона № 273-ФЗ(академический отпуск)</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax">0</td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Переведен на бюджет</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['countP']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Выпускник</td>
                <?php foreach ($num as $month):?>
                    <?php if (is_string($month))continue;?>


                    <td class="tg-0lax"><?=$month['countV']?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
        <?php endforeach;?>

    <?php endforeach;?>


<!--











    -->
    </tbody>
</table>
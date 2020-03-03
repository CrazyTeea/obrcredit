<?php

/***
 * @var \app\models\app\students\Students $student
 */
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['index']];
$this->title = 'Выгрузка';
$this->params['breadcrumbs'][] = $this->title;

function getCount($arr,$month,$id_bank,$id_num,$attr=false,$val=0){
    $cnt = 0;
    foreach ($arr as $item){
        if (!$attr) {
            if ($item->id_bank == $id_bank and
                $item->id_number_pp == $id_num and
                date('m', strtotime($item->date_start)) == $month
            ) {
                $cnt++;
               // yield $cnt;
            }
        }else{
            if ($item->id_bank == $id_bank and
                $item->id_number_pp == $id_num and
                date('m', strtotime($item->date_start)) == $month and
                $item->{$attr}==$val
            ) {
                $cnt++;
                // yield $cnt;
            }
        }
    }
    return $cnt;
}

function xrange($start,$stop,$step=1){
    if ($start <= $stop) {
        if ($step <= 0) {
            throw new LogicException('Шаг должен быть положительным');
        }

        for ($i = $start; $i <= $stop; $i += $step) {
            yield $i;
        }
    } else {
        if ($step >= 0) {
            throw new LogicException('Шаг должен быть отрицательным');
        }

        for ($i = $start; $i >= $stop; $i += $step) {
            yield $i;
        }
    }
}


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


    <?php foreach ($banks as $i=>$bank):?>
        <tr>
            <th class="tg-0lax" colspan="27"><?=$bank->name?></th>
        </tr>

        <?php foreach($nums as $num):?>
            <tr>
                <td class="tg-0lax" colspan="3">ПОСТАНОВЛЕНИЕ <?=$num->number?></td>
            <?php foreach (xrange(1,12) as $month):?>



                <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id);?></td>
                <td class="tg-0lax"></td>

            <?php endforeach;?>
            </tr>

            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Обучается</td>
            <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'education_status',1);?></td>
                    <td class="tg-0lax"></td>

            <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">п. 20 пункт 2 части 2 статьи 61 Федерального закона № 273-ФЗ, а<br>  также по инициативе обучающегося или родители</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по<br>  профессиональной образовательной программе обязанностей по добросовестному<br>  освоению такой образовательной программы и выполнению учебного плана</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',1);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">установление нарушения порядка приема в образовательную организацию, повлекшего по вине<br>  обучающегося его незаконное зачисление в образовательную организацию</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',2);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">отчислен по инициативе обучающегося или родителей (законных представителей)<br>  несовершеннолетнего обучающегося</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',3);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">п. 21 перевод обучающегося для продолжения освоения <br>основной профессиональной образовательной программы в другую<br>образовательную организацию:</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">в связи с ликвидацией образовательной организации</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',4);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">по независящим от воли обучающегося или родителей (законных представителей) <br>несовершеннолетнего обучающегося и образовательной организации</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',5);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">пункт 22 обучающимся (заемщиком) принято решение об отказе от<br>продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося<br>или родителей (законных представителей) несовершеннолетнего обучающегося и<br>образовательной организации, в том числе в случае ликвидации образовательной организации</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'osnovanie',6);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">пункт 12 часть 1 статья 34 Федерального закона № 273-ФЗ(академический отпуск)</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax">0</td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Переведен на бюджет</td>
                <?php foreach (xrange(1,12) as $month):?>


                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'perevod',1);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax" colspan="2">Выпускник</td>
                <?php foreach (xrange(1,12) as $month):?>

                    <td class="tg-0lax"><?=getCount($student,$month,$bank->id,$num->id,'isEnder',1);?></td>
                    <td class="tg-0lax"></td>

                <?php endforeach;?>
            </tr>
        <?php endforeach;?>

    <?php endforeach;?>


    </tbody>
</table>
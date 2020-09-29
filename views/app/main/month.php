<?php

use kartik\export\ExportMenu;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;


$this->params['breadcrumbs'][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => 'Выбор года', 'url' => ['index']];
$this->title = 'Выбор месяца';
$this->params['breadcrumbs'][] = $this->title;

$cans = Yii::$app->session['cans'];


/**
 * @var array $studentsByMonth
 * @var array $banks
 * @var array $export
 * @var array $nums
 */
$year = Yii::$app->session['year'] * 1;
$startMonth = 1;
$endMonth = 12;
if ($year == 2018)
    $startMonth = 10;

function getMonth($month)
{
    $arr = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь'
    ];

    $m = DateTime::createFromFormat('!m', $month);
    return $arr[$m->format('n') - 1];
}

function getPersent(int $year, int $month, int $nPP, array $students, int $status = 1, $persent = true, $bank = false)
{
    $pers = 0;
    $all = getCountStudents($year, $month, $nPP, $students);

    $byBank = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0
    ];

    foreach ($students as $student) {
        if ($student['year'] == $year and $student['month'] == $month and $student['id_number_pp'] == $nPP and $student['status'] == $status)
            $byBank[$student['id_bank']] += $student['count'];
    }

    if ($bank) {
        $pers = $byBank[$bank];
    } else {
        foreach ($byBank as $item) {
            $pers += $item;
        }
    }

    return $persent ? ($pers * 100 / $all) : ($pers);

}

function getCountStudents(int $year, int $month, int $nPP, array $students)
{
    $c = 0;
    foreach ($students as $student) {
        if ($student['year'] == $year and $student['month'] == $month and $student['id_number_pp'] == $nPP)
            $c += $student['count'];
    }
    return $c;
}

function getStudentByMonthYearAndNpp(int $year, int $month, int $nPP, array $students)
{
    foreach ($students as $student) {
        if ($student['year'] == $year and $student['month'] == $month and $student['id_number_pp'] == $nPP)
            return (object)$student;
    }
    return null;
}

function getBanks(int $year, int $month, int $nPP, array $students)
{
    $banks = [];
    $buf = [];
    foreach ($students as $i => $student) {
        if ($student['year'] == $year and $student['month'] == $month and $student['id_number_pp'] == $nPP) {
            if (!in_array($student['bank_name'], $buf)) {
                $banks[$i]['id'] = $student['id_bank'];
                $banks[$i]['name'] = $student['bank_name'];
                $buf[] = $student['bank_name'];
            }
        }
    }
    return $banks;
}

function getNumPPCount($npp, $nums)
{
    foreach ($nums as $num) {
        if ($num['number'] == $npp)
            return $num['students_count'];
    }
    return 0;
}

$payment_modals = null;

?>

<h2>Обучающиеся по государственной поддержке образовательного кредитования за <?= $year ?> год</h2>
<?php if (!$cans[2]): ?>
    <?= Html::a('Выгрузка за год', ['export', 'year' => $year], ['class' => 'btn btn-default']) ?>
<?php endif; ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php foreach (range(1, 12) as $month): ?>

                <?php $student197 = getStudentByMonthYearAndNpp($year, $month, 1, $studentsByMonth); ?>
                <?php $student699 = getStudentByMonthYearAndNpp($year, $month, 3, $studentsByMonth); ?>
                <?php $student1026 = getStudentByMonthYearAndNpp($year, $month, 2, $studentsByMonth); ?>
                <?php if ($student1026 || $student197 || $student699): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <div class="row">
                                    <div class="col-md-6"><?= ExportMenu::widget(['dataProvider' => $export['e_providers'][$month],
                                            'columns' => \app\models\app\students\Students::getColumns(true), 'timeout' => 300, 'exportConfig' => [
                                                ExportMenu::FORMAT_HTML => false,
                                                ExportMenu::FORMAT_CSV => false,
                                                ExportMenu::FORMAT_EXCEL => false,
                                                ExportMenu::FORMAT_TEXT => false,
                                            ]]) ?></div>
                                    <div class="col-md-6"><h2><?= getMonth($month) ?></h2></div>
                                </div>
                                <hr>

                                <?php if ($student197): ?>
                                    <p>
                                        <button id="<?= ($student197->status == 2) ? 'green' : 'red' ?>" type="button"
                                                class="btn btn-block btn-lg" data-toggle="modal"
                                                data-target="#myModal<?= $month ?>197">
                                            Постановление <br>правительства №197 <br>

                                            <span class="text "
                                                  style="font-size: 16px;"> <i> кол-во обучающихся: <?= getCountStudents($year, $month, 1, $studentsByMonth) ?> </i> </span>
                                            <br>
                                            <?php if (!$cans[2]): ?>
                                                <?php if ($month == 1): ?>
                                                    <span class="text " style="font-size: 16px;"> <i> кол-во отчисленных: <?= $janStudents['otch'] ?> </i> </span>
                                                    <br>
                                                    <span class="text " style="font-size: 16px;"> <i> кол-во выпускников: <?= $janStudents['vip'] ?> </i> </span>
                                                <?php endif ?>
                                                <div class="center-block"
                                                     style="border-radius: 10px; width:50%; background-color: #A3D8FF">
                                                    <?php
                                                    $flag = true;
                                                    $banks_s = getBanks($year, $month, 1, $studentsByMonth);
                                                    foreach ($banks_s as $b_s) {
                                                        if (!$payments_status[$month][1][$b_s['id']]) {
                                                            $flag = false;
                                                            break;
                                                        }
                                                    }
                                                    echo $flag ? "<span style='font-size: 16px;'> Оплачено </span>" : "<span style='font-size: 16px;'> Не оплачено </span>";
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="progress" style="margin-top: 5px">
                                                <?php
                                                $d = getPersent($year, $month, 1, $studentsByMonth);
                                                $utv = getPersent($year, $month, 1, $studentsByMonth, 2, false);
                                                $neut = getPersent($year, $month, 1, $studentsByMonth, 1, false);
                                                $d2 = 100 - $d;

                                                ?>
                                                <div class="progress-bar progress-bar-danger"
                                                     style="width: <?= $d ?>%"></div>
                                                <div class="progress-bar progress-bar-success"
                                                     style="width: <?= $d2 ?>%">
                                                </div>
                                                <div class="progress-bar progress-bar-label">
                                                    <span><?= "утв: $utv неут: $neut | " . round($d2, 2) ?></span>
                                                </div>

                                            </div>

                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?= $month ?>197" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document"
                                             style="margin-top: 15%;margin-bottom: 50%; position: initial;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year, $month, 1, $studentsByMonth) as $bank): ?>
                                                        <?php

                                                        $id_modal = substr(md5(rand()), 0, 7);
                                                        $payment_modals[] = [
                                                            'id' => $id_modal,
                                                            'id_bank' => $bank['id'],
                                                            'month' => $month,
                                                            'bank_name' => $bank['name']
                                                        ]; ?>
                                                        <?php if (!$cans[2]): ?>
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <?php if (!$cans[2]): ?>
                                                                        <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 1], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php else: ?>
                                                                        <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 1, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button id="<?= $payments_status[$month][1][$bank['id']] ? 'green' : 'red'; ?>"
                                                                            class="btn btn-block" data-toggle="modal"
                                                                            data-target="#payment_<?= $id_modal ?>">
                                                                        <span class="glyphicon glyphicon-ruble"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <?php if (!$cans[2]): ?>
                                                                <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 1], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php else: ?>
                                                                <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 1, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!$cans[2]): ?>
                                        <?php foreach ($payment_modals as $payment_modal): ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="payment_<?= $payment_modal['id'] ?>"
                                                 tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <?php
                                                        $form = ActiveForm::begin(
                                                            [
                                                                'action' => Url::to(['/app/payment/create']),
                                                                'method' => 'post',
                                                                'id' => $payment_modal['id']
                                                            ]);
                                                        $model = $payments[$month][1][$payment_modal['id_bank']]
                                                        ?>
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"
                                                                id="myModalLabel"><?= $payment_modal['bank_name'] ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div style="display: none" class="alert alert-success">
                                                                <p>
                                                                    <span id="response_message">Данные созранены успешно</span>
                                                                </p>
                                                            </div>
                                                            <?= $form->field($model, 'payment_date', ['options' => ['id' => "latter_payment_date_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => "{$year}-{$month}-5", 'id' => "latter_payment_date_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'numberpp_id', ['options' => ['id' => "latter_numberpp_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => 1, 'id' => "latter_numberpp_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'bank_id', ['options' => ['id' => "latter_bank_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => $payment_modal['id_bank'], 'id' => "latter_bank_id_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты письма Банка на оплату:</h4>
                                                            <?= $form->field($model, 'latter_number', ['options' => ['id' => "latter_number_{$payment_modal['id']}"]])->textInput(['id' => "latter_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'latter_date', ['options' => ['id' => "latter_date_{$payment_modal['id']}"]])->input('date', ['id' => "latter_date_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты платежного поручения:</h4>
                                                            <?= $form->field($model, 'order_number', ['options' => ['id' => "order_number_{$payment_modal['id']}"]])->textInput(['id' => "order_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'order_date', ['options' => ['id' => "order_date_{$payment_modal['id']}"]])->input('date', ['id' => "order_date_{$payment_modal['id']}"]) ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Вернуться к списку
                                                            </button>
                                                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'payment_submit']) ?>
                                                        </div>
                                                        <?php ActiveForm::end() ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                        $payment_modals = null;
                                        ?>
                                    <?php endif; ?>
                                <?php endif ?>
                                <?php if ($student699): ?>
                                    <p>

                                        <button id="<?= ($student699->status == 2) ? 'green' : 'red' ?>" type="button"
                                                class="btn btn-block btn-lg" data-toggle="modal"
                                                data-target="#myModal<?= $month ?>699">
                                            Постановление <br>правительства №699 <br>
                                            <span class="text "
                                                  style="font-size: 16px;"> <i> кол-во обучающихся: <?= getCountStudents($year, $month, 3, $studentsByMonth) ?> </i> </span>
                                            <br>
                                            <?php if (!$cans[2]): ?>
                                                <div class="center-block"
                                                     style="border-radius: 10px; width:50%; background-color: #A3D8FF">
                                                    <?php
                                                    $flag = true;
                                                    $banks_s = getBanks($year, $month, 3, $studentsByMonth);
                                                    foreach ($banks_s as $b_s) {
                                                        if (!$payments_status[$month][3][$b_s['id']]) {
                                                            $flag = false;
                                                            break;
                                                        }
                                                    }

                                                    echo $flag ? "<span style='font-size: 16px;'> Оплачено </span>" : "<span style='font-size: 16px;'> Не оплачено </span>";
                                                    ?>
                                                </div>
                                            <?php endif; ?>

                                            <i>Все</i>

                                            <div class="progress" style="margin-top: 5px">
                                                <?php
                                                $d = getPersent($year, $month, 3, $studentsByMonth);
                                                $utv = getPersent($year, $month, 3, $studentsByMonth, 2, false);
                                                $neut = getPersent($year, $month, 3, $studentsByMonth, 1, false);
                                                $d2 = 100 - $d;

                                                ?>
                                                <div class="progress-bar progress-bar-danger"
                                                     style="width: <?= $d ?>%"></div>
                                                <div class="progress-bar progress-bar-success"
                                                     style="width: <?= $d2 ?>%">
                                                </div>
                                                <div class="progress-bar progress-bar-label">
                                                    <span><?= "утв: $utv неут: $neut | " . round($d2, 2) ?></span>
                                                </div>
                                            </div>


                                            <i>Сбербанк</i>

                                            <div class="progress" style="margin-top: 5px">
                                                <?php
                                                $d = getPersent($year, $month, 3, $studentsByMonth,1,true,1);
                                                $utv = getPersent($year, $month, 3, $studentsByMonth, 2, false,1);
                                                $neut = getPersent($year, $month, 3, $studentsByMonth, 1, false,1);
                                                $d2 = 100 - $d;

                                                ?>
                                                <div class="progress-bar progress-bar-danger"
                                                     style="width: <?= $d ?>%"></div>
                                                <div class="progress-bar progress-bar-success"
                                                     style="width: <?= $d2 ?>%">
                                                </div>
                                                <div class="progress-bar progress-bar-label">
                                                    <span><?= "утв: $utv неут: $neut | " . round($d2, 2) ?></span>
                                                </div>
                                            </div>

                                            <i>Союз</i>

                                            <div class="progress" style="margin-top: 5px">
                                                <?php
                                                $d = getPersent($year, $month, 3, $studentsByMonth,1,true,2);
                                                $utv = getPersent($year, $month, 3, $studentsByMonth, 2, false,2);
                                                $neut = getPersent($year, $month, 3, $studentsByMonth, 1, false,2);
                                                $d2 = 100 - $d;

                                                ?>
                                                <div class="progress-bar progress-bar-danger"
                                                     style="width: <?= $d ?>%"></div>
                                                <div class="progress-bar progress-bar-success"
                                                     style="width: <?= $d2 ?>%">
                                                </div>
                                                <div class="progress-bar progress-bar-label">
                                                    <span><?= "утв: $utv неут: $neut | " . round($d2, 2) ?></span>
                                                </div>
                                            </div>

                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?= $month ?>699" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document"
                                             style="margin-top: 15%;margin-bottom: 50%; position: initial;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year, $month, 3, $studentsByMonth) as $bank): ?>
                                                        <?php
                                                        $id_modal = substr(md5(rand()), 0, 7);
                                                        $payment_modals[] = [
                                                            'id' => $id_modal,
                                                            'id_bank' => $bank['id'],
                                                            'month' => $month,
                                                            'bank_name' => $bank['name']
                                                        ];
                                                        ?>
                                                        <?php if (!$cans[2]): ?>
                                                            <div class="row">
                                                                <div class="col-md-9">

                                                                    <?php if (!$cans[2]): ?>
                                                                        <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 3], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php else: ?>
                                                                        <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 3, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button id="<?= $payments_status[$month][3][$bank['id']] ? 'green' : 'red'; ?>"
                                                                            class="btn btn-block" data-toggle="modal"
                                                                            data-target="#payment_<?= $id_modal ?>">
                                                                        <span class="glyphicon glyphicon-ruble"></span>
                                                                    </button>

                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <?php if (!$cans[2]): ?>
                                                                <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 3], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php else: ?>
                                                                <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 3, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!$cans[2]): ?>
                                        <?php foreach ($payment_modals as $payment_modal): ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="payment_<?= $payment_modal['id'] ?>"
                                                 tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <?php
                                                        $form = ActiveForm::begin(
                                                            [
                                                                'action' => Url::to(['/app/payment/create']),
                                                                'method' => 'post',
                                                                'id' => $payment_modal['id']
                                                            ]);
                                                        $model = $payments[$month][3][$payment_modal['id_bank']]
                                                        ?>
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"
                                                                id="myModalLabel"><?= $payment_modal['bank_name'] ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div style="display: none" class="alert alert-success">
                                                                <p>
                                                                    <span id="response_message">Данные созранены успешно</span>
                                                                </p>
                                                            </div>
                                                            <?= $form->field($model, 'payment_date', ['options' => ['id' => "latter_payment_date_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => "{$year}-{$month}-5", 'id' => "latter_payment_date_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'numberpp_id', ['options' => ['id' => "latter_numberpp_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => 3, 'id' => "latter_numberpp_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'bank_id', ['options' => ['id' => "latter_bank_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => $payment_modal['id_bank'], 'id' => "latter_bank_id_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты письма Банка на оплату:</h4>
                                                            <?= $form->field($model, 'latter_number', ['options' => ['id' => "latter_number_{$payment_modal['id']}"]])->textInput(['id' => "latter_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'latter_date', ['options' => ['id' => "latter_date_{$payment_modal['id']}"]])->input('date', ['id' => "latter_date_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты платежного поручения:</h4>
                                                            <?= $form->field($model, 'order_number', ['options' => ['id' => "order_number_{$payment_modal['id']}"]])->textInput(['id' => "order_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'order_date', ['options' => ['id' => "order_date_{$payment_modal['id']}"]])->input('date', ['id' => "order_date_{$payment_modal['id']}"]) ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Вернуться к списку
                                                            </button>
                                                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'payment_submit']) ?>
                                                        </div>
                                                        <?php ActiveForm::end() ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                        $payment_modals = null;
                                        ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($student1026): ?>
                                    <p>

                                        <button id="<?= ($student1026->status == 2) ? 'green' : 'red' ?>" type="button"
                                                class="btn btn-block btn-lg" data-toggle="modal"
                                                data-target="#myModal<?= $month ?>1026">
                                            Постановление <br>правительства №1026 <br>
                                            <span class="text "
                                                  style="font-size: 16px;"> <i> кол-во обучающихся: <?= getCountStudents($year, $month, 2, $studentsByMonth) ?> </i> </span>
                                            <br>
                                            <?php if (!$cans[2]): ?>
                                                <div class="center-block"
                                                     style="border-radius: 10px; width:50%; background-color: #A3D8FF">
                                                    <?php
                                                    $flag = true;
                                                    $banks_s = getBanks($year, $month, 2, $studentsByMonth);
                                                    foreach ($banks_s as $b_s) {
                                                        if (!$payments_status[$month][2][$b_s['id']]) {
                                                            $flag = false;
                                                            break;
                                                        }
                                                    }

                                                    echo $flag ? "<span style='font-size: 16px;'> Оплачено </span>" : "<span style='font-size: 16px;'> Не оплачено </span>";
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="progress" style="margin-top: 5px">
                                                <?php
                                                $d = getPersent($year, $month, 2, $studentsByMonth);
                                                $utv = getPersent($year, $month, 2, $studentsByMonth, 2, false);
                                                $neut = getPersent($year, $month, 2, $studentsByMonth, 1, false);
                                                $d2 = 100 - $d;

                                                ?>

                                                <div class="progress-bar progress-bar-danger"
                                                     style="width: <?= $d ?>%"></div>
                                                <div class="progress-bar progress-bar-success"
                                                     style="width: <?= $d2 ?>%">
                                                </div>
                                                <div class="progress-bar progress-bar-label">
                                                    <span><?= "утв: $utv неут: $neut | " . round($d2, 2) ?></span>
                                                </div>
                                            </div>
                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?= $month ?>1026" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document"
                                             style="margin-top: 15%;margin-bottom: 50%; position: initial;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year, $month, 2, $studentsByMonth) as $bank): ?>
                                                        <?php
                                                        $id_modal = substr(md5(rand()), 0, 7);
                                                        $payment_modals[] = [
                                                            'id' => $id_modal,
                                                            'id_bank' => $bank['id'],
                                                            'month' => $month,
                                                            'bank_name' => $bank['name']
                                                        ];
                                                        ?>
                                                        <?php if (!$cans[2]): ?>
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <?php if (!$cans[2]): ?>
                                                                        <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 2], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php else: ?>
                                                                        <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 2, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button id="<?= $payments_status[$month][2][$bank['id']] ? 'green' : 'red'; ?>"
                                                                            class="btn btn-block" data-toggle="modal"
                                                                            data-target="#payment_<?= $id_modal ?>">
                                                                        <span class="glyphicon glyphicon-ruble"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <?php if (!$cans[2]): ?>
                                                                <?= Html::a($bank['name'], ['app/organizations/by-bank', 'id_bank' => $bank['id'], 'month' => $month, 'nPP' => 2], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php else: ?>
                                                                <?= Html::a($bank['name'], ['app/students/by-bank', 'id' => $bank['id'], 'nPP' => 2, 'month' => $month], ['class' => 'btn btn-primary btn-block']) ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!$cans[2]): ?>
                                        <?php foreach ($payment_modals as $payment_modal): ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="payment_<?= $payment_modal['id'] ?>"
                                                 tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <?php
                                                        $form = ActiveForm::begin(
                                                            [
                                                                'action' => Url::to(['/app/payment/create']),
                                                                'method' => 'post',
                                                                'id' => $payment_modal['id']
                                                            ]);
                                                        $model = $payments[$month][2][$payment_modal['id_bank']]
                                                        ?>
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"
                                                                id="myModalLabel"><?= $payment_modal['bank_name'] ?></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div style="display: none" class="alert alert-success">
                                                                <p>
                                                                    <span id="response_message">Данные созранены успешно</span>
                                                                </p>
                                                            </div>
                                                            <?= $form->field($model, 'payment_date', ['options' => ['id' => "latter_payment_date_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => "{$year}-{$month}-5", 'id' => "latter_payment_date_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'numberpp_id', ['options' => ['id' => "latter_numberpp_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => 2, 'id' => "latter_numberpp_id_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'bank_id', ['options' => ['id' => "latter_bank_id_{$payment_modal['id']}"], 'enableLabel' => false])->hiddenInput(['value' => $payment_modal['id_bank'], 'id' => "latter_bank_id_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты письма Банка на оплату:</h4>
                                                            <?= $form->field($model, 'latter_number', ['options' => ['id' => "latter_number_{$payment_modal['id']}"]])->textInput(['id' => "latter_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'latter_date', ['options' => ['id' => "latter_date_{$payment_modal['id']}"]])->input('date', ['id' => "latter_date_{$payment_modal['id']}"]) ?>
                                                            <h4>Реквизиты платежного поручения:</h4>
                                                            <?= $form->field($model, 'order_number', ['options' => ['id' => "order_number_{$payment_modal['id']}"]])->textInput(['id' => "order_number_{$payment_modal['id']}"]) ?>
                                                            <?= $form->field($model, 'order_date', ['options' => ['id' => "order_date_{$payment_modal['id']}"]])->input('date', ['id' => "order_date_{$payment_modal['id']}"]) ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Вернуться к списку
                                                            </button>
                                                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'payment_submit']) ?>
                                                        </div>
                                                        <?php ActiveForm::end() ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                        $payment_modals = null;
                                        ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

        </div>
    </div>
</div>

<style>
    .progress-bar-label {
        display: flex;
        position: absolute;
        height: 20px;
        width: 25%;
        left: 25%;
        background-color: transparent !important;
    }
</style>


<?php

use kartik\export\ExportMenu;
use yii\bootstrap\Html;
use yii\helpers\Url;



$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label'=>'Выбор года','url'=>['index']];
$this->title = 'Выбор месяца';
$this->params['breadcrumbs'][] = $this->title;

$cans = Yii::$app->session['cans'];


/**
 * @var array $studentsByMonth
 * @var array $banks
 */
$year = Yii::$app->session['year'];
$startMonth = 1;
$endMonth = 12;
if ($year == 2018)
    $startMonth = 10;


?>

<h2>Обучающиеся по государственной поддержке образовательного кредитования за <?=$year?> год</h2>

<div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <?php for ($i = $startMonth;$i<=$endMonth;$i++):?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <div class="col-md-6"><?=ExportMenu::widget(['dataProvider'=>$studentsByMonth[$i]['exportPr'],'columns'=>$exportColumns]) ?></div>
                                <div class="col-md-6">
                                    <h2><?php

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

                                        $m = DateTime::createFromFormat('!m',$i);
                                        echo $arr[$m->format('n')-1]?>
                                    </h2>
                                </div>
                            </div>


                            <hr>
                            <?php if($year != 2018):?>
                            <p>
                                <!-- Button trigger modal -->
                                <button id="<?= ($studentsByMonth[$i][197]['students']['notApproved'])? 'red' : 'green' ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>197">
                                    Постановление <br>правительства №197 <br>
                                    <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentsByMonth[$i][197]['students']['count']?> </i> </span>
                                </button>

                            </p>
                            <div class="modal fade" id="myModal<?=$i?>197" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php foreach ($studentsByMonth[$i][197]['bank'] as $item ): ?>
                                                <?php if ($cans[0] || $cans[1]):?>
                                                    <?= Html::a($banks[$item-1],['app/organizations/by-bank','id_bank'=>$item,'month'=>$i,'nPP'=>1],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'m'=>$i,'nPP'=>1],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php endif;?>

                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>


                            <p>
                                <button id="<?= ($studentsByMonth[$i][699]['students']['notApproved'])? 'red' : 'green' ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>699">
                                    Постановление <br> правительства № 699 <br>
                                    <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentsByMonth[$i][699]['students']['count']?> </i> </span>
                                </button>

                            <div class="modal fade" id="myModal<?=$i?>699" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                        </div>
                                        <div class="modal-body">

                                            <?php  foreach ($studentsByMonth[$i][699]['bank'] as $item ): ?>
                                                <?php if ($cans[0] || $cans[1]):?>
                                                    <?= Html::a($banks[$item-1],['app/organizations/by-bank','id_bank'=>$item,'month'=>$i,'nPP'=>2],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'m'=>$i,'nPP'=>2],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            </p>
                            <p>
                                <!-- Button trigger modal -->
                                <button id="<?= ($studentsByMonth[$i][1026]['students']['notApproved'])? 'red' : 'green' ?>" type="button" class="btn  btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>1026">
                                    Постановление <br> правительства № 1026<br>
                                    <span class="text" style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentsByMonth[$i][1026]['students']['count']?> </i> </span>
                                </button>
                            <div class="modal fade" id="myModal<?=$i?>1026" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php foreach ($studentsByMonth[$i][1026]['bank'] as $item ): ?>
                                                <?php if ($cans[0] || $cans[1]):?>
                                                    <?= Html::a($banks[$item-1],['app/organizations/by-bank','id_bank'=>$item,'month'=>$i,'nPP'=>3],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'m'=>$i],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endfor;?>
        </div>
    </div>
</div>


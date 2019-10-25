<?php

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

?>



<div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <?php for ($i = 1;$i<=12;$i++):?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail">
                        <div class="caption">
                            <h5><?php

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
                                echo $arr[$m->format('n')-1]?></h5>
                            <p>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$i?>197">
                                    П/П 197
                                </button>
                                <span id="count" class="badge alert-success"><?=$studentsByMonth[$i][197]['students']?></span>

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
                                                    <?= Html::a($banks[$item],['app/organizations/by-bank','id'=>$item],['class'=>'btn btn-primary']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item],['app/students/by-bank','id'=>$item],['class'=>'btn btn-primary']) ?>
                                                <?php endif;?>
                                                <br>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>

                            <p>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$i?>699">
                                    П/П 699
                                </button>
                                <span id="count" class="badge alert-success"><?=$studentsByMonth[$i][699]['students']?></span>
                            <div class="modal fade" id="myModal<?=$i?>699" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php foreach ($studentsByMonth[$i][699]['bank'] as $item ): ?>
                                                <?= Html::a($banks[$item],['app/organizations/by-bank','id'=>$item],['class'=>'btn btn-primary']) ?>
                                                <br>
                                            <?php endforeach;?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            </p>
                            <p>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$i?>1026">
                                    П/П 1026
                                </button>
                                <span id="count" class="badge alert-success"><?=$studentsByMonth[$i][1026]['students']?></span>
                            <div class="modal fade" id="myModal<?=$i?>1026" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php foreach ($studentsByMonth[$i][1026]['bank'] as $item ): ?>
                                                <?= Html::a($banks[$item],['app/organizations/by-bank','id'=>$item],['class'=>'btn  btn-primary']) ?>
                                                <br>
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


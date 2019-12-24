<?php

use kartik\export\ExportMenu;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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
$year = Yii::$app->session['year']*1;
$startMonth = 1;
$endMonth = 12;
if ($year == 2018)
    $startMonth = 10;

function getMonth($month){
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

    $m = DateTime::createFromFormat( '!m', $month );
    return $arr[ $m->format( 'n' ) - 1 ];
}
function getCountStudents(int $year, int $month, int $nPP, array $students){
    $c = 0;
    foreach ($students as $student){
        if ($student->year == $year and $student->month == $month and $student->id_number_pp == $nPP)
            $c+=$student->count;
    }
    return $c;
}
function getStudentByMonthYearAndNpp( int $year, int $month, int $nPP, array $students){
    foreach ($students as $student){
        if ($student->year == $year and $student->month == $month and $student->id_number_pp == $nPP)
            return $student;
    }
    return null;
}
function getBanks(int $year,int $month,int $nPP, array $students){
    $banks = [];
    foreach ($students as $i=> $student){
        if ($student->year == $year and $student->month == $month and $student->id_number_pp == $nPP){
            $banks[$i]['id']=$student->id_bank;
            $banks[$i]['name']=$student->bank_name;
        }
    }
    return $banks;
}
function getNumPPCount($npp,$nums){
    foreach ($nums as $num){
        if ($num['number'] == $npp)
            return $num['students_count'];
    }
    return 0;
}
?>

<h2>Обучающиеся по государственной поддержке образовательного кредитования за <?=$year?> год</h2>

<div class="row">



    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Журнал изменений
                                    <span  title="В данном журнале отображаются все не найденные в организации обучающиеся" class="glyphicon glyphicon-question-sign" data-placement="bottom" data-toggle="tooltip"></span>
                                </h2>
                            </div>
                        </div>
                        <hr>
                        <?php if ($year !== 2018): ?>
                        <p>
                            <?=Html::a('Постановление <br> правительства №197 <br> '.'<span class="text " style="font-size: 16px;"><i> кол-во обучающихся:'. getNumPPCount(197,$nums) .'</i></span>',['/app/students-history/get-by-number-and-year','id_number_pp'=>1,'year'=>$year],['id'=>'zhurnal_button','class'=>'btn btn-block btn-lg'])?>

                        </p>
                        <?php endif; ?>
                        <p>
                            <?=Html::a('Постановление <br> правительства №699 <br> '.'<span class="text " style="font-size: 16px;"><i> кол-во обучающихся:'. getNumPPCount(699,$nums) .'</i></span>',['/app/students-history/get-by-number-and-year','id_number_pp'=>3,'year'=>$year],['id'=>'zhurnal_button','class'=>'btn btn-block btn-lg'])?>
                            <span> <?php//= \yii\helpers\ArrayHelper::getValue($nums,function ($nums,$defaultValue){return $nums;}) ?></span>
                        </p>
                        <p>
                            <?=Html::a('Постановление <br> правительства №1026 <br> '.'<span class="text " style="font-size: 16px;"><i> кол-во обучающихся:'. getNumPPCount(1026,$nums) .'</i></span>',['/app/students-history/get-by-number-and-year','id_number_pp'=>2,'year'=>$year],['id'=>'zhurnal_button','class'=>'btn btn-block btn-lg'])?>
                            <span> <?php//= \yii\helpers\ArrayHelper::getValue($nums,function ($nums,$defaultValue){return $nums;}) ?></span>
                        </p>

                    </div>
                </div>
            </div>
            <?php for ($month = 1;$month<=12;$month++):?>

                <?php $student197 = getStudentByMonthYearAndNpp($year,$month,1,$studentsByMonth);?>
                <?php $student699 = getStudentByMonthYearAndNpp($year,$month,3,$studentsByMonth);?>
                <?php $student1026 = getStudentByMonthYearAndNpp($year,$month,2,$studentsByMonth);?>
                <?php if ($student1026 || $student197 || $student699):?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <div class="row">
                                    <div class="col-md-6"><?=ExportMenu::widget(['dataProvider'=>$exportQuery[$month],'columns'=>\app\models\app\students\Students::getColumns(true),'timeout'=>300])?></div>
                                    <div class="col-md-6"><h2><?=getMonth($month)?></h2></div>
                                </div>
                                <hr>

                                <?php if ($student197):?>
                                    <p>

                                        <button id="<?=($student197 and $student197->status==1) ? 'red' :  'green'  ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$month?>197">
                                            Постановление <br>правительства №197 <br>
                                            <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?=getCountStudents($year,$month,1,$studentsByMonth)?> </i> </span>
                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?=$month?>197" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year,$month,1,$studentsByMonth) as $bank):?>
                                                        <?php if ($cans[0] || $cans[1]):?>
                                                            <?= Html::a($bank['name'],['app/organizations/by-bank','id_bank'=>$bank['id'],'month'=>$month,'nPP'=>1],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php else:?>
                                                            <?= Html::a($bank['name'],['app/students/by-bank','id'=>$bank['id'],'nPP'=>1,'month'=>$month],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php endif;?>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($student699):?>
                                    <p>

                                        <button id="<?=($student699 and $student699->status==1) ? 'red' :  'green'  ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$month?>699">
                                            Постановление <br>правительства №699 <br>
                                            <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?= getCountStudents($year,$month,3,$studentsByMonth)?> </i> </span>
                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?=$month?>699" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year,$month,3,$studentsByMonth) as $bank):?>
                                                        <?php if ($cans[0] || $cans[1]):?>
                                                            <?= Html::a($bank['name'],['app/organizations/by-bank','id_bank'=>$bank['id'],'month'=>$month,'nPP'=>3],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php else:?>
                                                            <?= Html::a($bank['name'],['app/students/by-bank','id'=>$bank['id'],'nPP'=>3,'month'=>$month],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php endif;?>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($student1026):?>
                                    <p>

                                        <button id="<?=($student1026 and $student1026->status==1) ? 'red' :  'green'  ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$month?>1026">
                                            Постановление <br>правительства №1026 <br>
                                            <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?= getCountStudents($year,$month,2,$studentsByMonth)?> </i> </span>
                                        </button>
                                    </p>
                                    <div class="modal fade" id="myModal<?=$month?>1026" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Банки</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php foreach (getBanks($year,$month,2,$studentsByMonth) as $bank):?>
                                                        <?php if ($cans[0] || $cans[1]):?>
                                                            <?= Html::a($bank['name'],['app/organizations/by-bank','id_bank'=>$bank['id'],'month'=>$month,'nPP'=>2],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php else:?>
                                                            <?= Html::a($bank['name'],['app/students/by-bank','id'=>$bank['id'],'nPP'=>2,'month'=>$month],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php endif;?>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>


                            </div>
                        </div>
                    </div>
                <?php endif;?>
            <?php endfor;?>
            <?php /*?>
            <?php foreach($studentsByMonth as $studentByMonth):?>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php//=ExportMenu::widget(['dataProvider'=>$studentsByMonth[$i]['exportPr'],'columns'=>$exportColumns,'batchSize'=>10,'target'=>'_blank']) ?>
                                </div>
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

                                        $m = DateTime::createFromFormat('!m',$studentByMonth->month);
                                        echo $arr[$m->format('n')-1]?>
                                    </h2>
                                </div>
                            </div>


                            <hr>
                            <?php if($year != 2018):?>
                                <?php if (!in_array($studentByMonth->month,[1,2,3,4,5,6,7])):?>
                                    <p>
                                        <!-- Button trigger modal -->
                                        <button id="<?= $studentByMonth->status == 1 ? 'red' :  'green'  ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>197">
                                            Постановление <br>правительства №197 <br>
                                            <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentByMonth->count?> </i> </span>
                                        </button>

                                    </p>
                                    <div class="modal fade" id="m
 yModal<?=$i?>197" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                                            <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'nPP'=>1,'month'=>$i],['class'=>'btn btn-primary btn-block']) ?>
                                                        <?php endif;?>

                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>


                            <p>
                                <button id="<?= ($studentsByMonth[$i][699]['students']['notApproved'])? 'red' :  'green' ?>" type="button" class="btn btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>699">
                                    Постановление <br> правительства № 699 <br>
                                    <span class="text " style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentsByMonth[$i][699]['students']['count']?> </i> </span>
                                </button>
                            </p>
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
                                                    <?= Html::a($banks[$item-1],['app/organizations/by-bank','id_bank'=>$item,'month'=>$i,'nPP'=>3],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'nPP'=>3,'month'=>$i],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <p>
                                <!-- Button trigger modal -->
                                <button id="<?= ($studentsByMonth[$i][1026]['students']['notApproved'])? 'red' : 'green'  ?>" type="button" class="btn  btn-block btn-lg" data-toggle="modal" data-target="#myModal<?=$i?>1026">
                                    Постановление <br> правительства № 1026<br>
                                    <span class="text" style="font-size: 16px;"> <i> кол-во обучающихся: <?=$studentsByMonth[$i][1026]['students']['count']?> </i> </span>
                                </button>
                            </p>
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
                                                    <?= Html::a($banks[$item-1],['app/organizations/by-bank','id_bank'=>$item,'month'=>$i,'nPP'=>2],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php else:?>
                                                    <?= Html::a($banks[$item-1],['app/students/by-bank','id'=>$item,'nPP'=>2,'month'=>$i],['class'=>'btn btn-primary btn-block']) ?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach;
 */?>

        </div>
    </div>
</div>


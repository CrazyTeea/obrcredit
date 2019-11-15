<?php

use yii\helpers\Url;

$this->title = "Выбор года";
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
$yearStart = 2018;
$yearEnd = 2021;
$curYear = date('Y');
?>
<h2 > Обучающиеся по государственной поддержке образовательного кредитования</h2>
<div class="row">
    <?php for ($i = $yearStart;$i<=$yearEnd;$i++):?>
        <?php if ($i <= $curYear):?>
            <a href="<?=Url::toRoute(['month','year'=>$i])?>">
        <?php else:?>
                <a href="" id="no">
        <?php endif;?>
            <div class="col-sm-6 col-md-3 ">
                <div class="thumbnail ">
                    <div class="caption">
                        <h5><?="$i год"?></h5>
                        <hr>
                        <p>
                            <span >Всего обучающихся:</span>
                            <span id="count" class="badge alert-info"><?=0//$studentsByYear[$i]['studentsCount']?></span>
                        </p>
                        <p>
                            <span >Утвержденные</span>
                            <span id="count" class="badge alert-success"><?=0//$studentsByYear[$i]['studentsApprovedCount']?></span>
                        </p>
                        <p>
                            <span >Неутвержденные</span>
                            <span id="count" class="badge alert-danger"><?=0//$studentsByYear[$i]['studentsUnapprovedCount']?></span>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    <?php endfor;?>
</div>



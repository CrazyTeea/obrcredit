<?php

use yii\helpers\Url;

$this->title = "Выбор года";
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
$yearStart = 2018;
$yearEnd = 2021;
?>
<div class="row ">
    <?php for ($i = $yearStart;$i<=$yearEnd;$i++):?>
        <a href="<?=Url::toRoute(['month','year'=>$i])?>">
            <div class="col-sm-6 col-md-3 ">
                <div class="thumbnail ">
                    <div class="caption">
                        <h5><?="$i год"?></h5>
                        <p>
                            <span >Студентов</span>
                            <span id="count" class="badge alert-info"><?=$studentsByYear[$i]['studentsCount']?></span>
                        </p>
                        <p>
                            <span >Утвержденные</span>
                            <span id="count" class="badge alert-success"><?=$studentsByYear[$i]['studentsApprovedCount']?></span>
                        </p>
                        <p>
                            <span >Неутвержденные</span>
                            <span id="count" class="badge alert-danger"><?=$studentsByYear[$i]['studentsUnapprovedCount']?></span>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    <?php endfor;?>
</div>



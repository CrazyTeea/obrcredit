<?php

use app\models\app\students\Students;
use yii\helpers\Url;

$this->title = "Выбор года";
$this->params[ 'breadcrumbs' ][] = ['label' => 'ОбрКредит', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
$yearStart = 2017;
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
                            <span id="count" class="badge alert-info"><?=Students::find()
                                    ->where(
                                        Yii::$app->user->can('podved') ?
                                            ['YEAR(date_start)'=>$i, 'id_org'=>Yii::$app->session['id_org']] :
                                            ['YEAR(date_start)'=>$i])
                                   ->count();?></span>
                        </p>
                        <p>
                            <span >Утвержденные</span>
                            <span id="count" class="badge alert-success"><?=Students::find()
                                    ->where(
                                        Yii::$app->user->can('podved') ?
                                            ['YEAR(date_start)'=>$i, 'status'=>2 , 'id_org'=>Yii::$app->session['id_org']] :
                                            ['status'=>2 ,'YEAR(date_start)'=>$i])
                                    ->count();?></span>
                        </p>
                        <p>
                            <span >Неутвержденные</span>
                            <span id="count" class="badge alert-danger"><?=Students::find()
                                    ->where(
                                        Yii::$app->user->can('podved') ?
                                            ['YEAR(date_start)'=>$i, 'status'=>1 , 'id_org'=>Yii::$app->session['id_org']] :
                                            ['status'=>1 ,'YEAR(date_start)'=>$i])
                                    ->count();?></span>
                        </p>
                    </div>
                </div>
            </div>
        </a>
        <?php endfor;?>
</div>



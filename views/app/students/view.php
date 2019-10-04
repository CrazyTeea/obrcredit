<?php

use app\models\app\students\StudentDocs;
use app\models\app\students\Students;
use app\models\User;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */

$this->title = $model->name;
$cans = Yii::$app->session['cans'];
if ($cans[0] || $cans[1])
    $this->params['breadcrumbs'][] = ['label' => 'Организация', 'url' => ['app/organizations/index']];
$this->params['breadcrumbs'][] = ['label' => 'Обучающиеся', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);


$rasp_act0 = StudentDocs::getDocByDescriptorName('rasp_act0',$model->id);
$rasp_act1 = StudentDocs::getDocByDescriptorName('rasp_act1',$model->id);
$rasp_act2 = StudentDocs::getDocByDescriptorName('rasp_act2',$model->id);
$rasp_act3 = StudentDocs::getDocByDescriptorName('rasp_act3',$model->id);
$dogovor = StudentDocs::getDocByDescriptorName('dogovor',$model->id);
$rasp_act_otch = StudentDocs::getDocByDescriptorName('rasp_act_otch',$model->id);
//var_dump($model->dateLastStatus->updated_at);
?>

<div class="students-view">

    <h1><?= Html::encode($this->title) ?></h1>


        <?= Html::a('Редактировать', ['update', 'id' => $model->id],['class'=>'btn btn-primary']) ?>

    <?php if ($cans[0] or $cans[1]):?>

        <?=  Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class'=>'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить студента?',
                'method' => 'post',
            ],
        ]) ?>

    <?php endif;?>


        <!--<?= Html::a('Экспорт',['export','id'=>$model->id],['class'=>'btn btn-default']) ?>
        -->
    <?= Html::a('Вернуться к списку',['app/students'],['class'=>'btn btn-default']) ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4"><p class="text-center ">Статус обучаещегося</p></th>
            <th colspan="3"><p class="text-center">Пролонгация льготного периода пользования <br> образовательным кредитом</p></th>
        </tr>
        <tr>
            <td rowspan='2'><p class="text-sm-center">Продолжает <br> обучаться </p></td>
            <td colspan="3"><p class="text-sm-center">Досрочно прекратил образовательные отношения </p></td>
            <td rowspan='2'><p class="text-sm-center">Отсрочка льготного периода <br> в связи с предоставлением академического права  <br>
                    (пункт 12 часть 1 статья 34 Федерального закона № 273-ФЗ) </p>
            </td>
            <td rowspan='2'><p class="text-sm-center">Срок действия академического права</p> </td>
            <td rowspan='2'><p class="text-sm-center">Подтверждение
                    срока окончания академического права
                </p></td>
        </tr>
        <tr>
            <td>Пункты постановления <br> Правительства РФ <br> от 26.02.2018 г. № 197</td>
            <td>Основание</td>
            <td>Подтверждение основания</td>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td rowspan="5">
                <?= $model->education_status ? 'Обучается' : 'Не обучается' ?> <br>
                <?= ($model->dateLastStatus and $model->dateLastStatus->updated_at) ? Yii::$app->getFormatter()->asDate(strtotime($model->dateLastStatus->updated_at)) : date('now') ?> </td>
            <td rowspan="3"><p class="text-sm-center"> пункт 20 </p></td>
            <td rowspan="2">
                <p class="text-sm-center">пункт 2 части 2 статьи 61 Федерального закона
                    № 273-ФЗ
                    <?php for ($i=1; $i< 3; $i++){
                        $k = Students::getOsnovanie()[$i];
                        if ($i==$model->osnovanie)
                            echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                        else echo "<p class='text-sm-center'> &#9744; $k</p>";
                    } ?>
                </p>
            </td>
            <td rowspan="3">
                <?= $rasp_act0 ? Html::a($rasp_act0->file->name,['download','id'=>$rasp_act0->id])  : 'Файл не загружен' ?>
            </td>
            <td rowspan="3">
                <?php
                $k = Students::getGracePeriod()[1];
                if (1==$model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td rowspan="3" style="text-align: center; vertical-align: middle;" ><p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period1) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period1) ?>
                </p>
            </td>
            <td rowspan="3">
                <?= $rasp_act1 ? Html::a($rasp_act1->file->name,['download','id'=>$rasp_act1->id])  : 'Файл не загружен' ?>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>
                <?php
                $k = Students::getOsnovanie()[3];
                if (3==$model->osnovanie)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 21 </p></td>
            <td><p class="text-sm-center">
                    перевод обучающегося для продолжения освоения основной профессиональной образовательной программы в другую образовательную организацию:
                    <?php for ($i=4; $i< 6; $i++){
                        $k = Students::getOsnovanie()[$i];
                        if ($i==$model->osnovanie)
                            echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                        else echo "<p class='text-sm-center'> &#9744; $k</p>";
                    } ?>
                </p></td>
            <td>
                <?= $dogovor ? Html::a($dogovor->file->name,['download','id'=>$dogovor->id])  : 'Файл не загружен' ?>
            </td>
            <td>
                <?php
                $k = Students::getGracePeriod()[2];
                if (2==$model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td  style="text-align: center; vertical-align: middle;" ><p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period2) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period2) ?>
                </p>
            </td>
            <td>
                <?= $rasp_act2 ? Html::a($rasp_act2->file->name,['download','id'=>$rasp_act2->id])  : 'Файл не загружен' ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 22 </p></td>
            <td>
                <?php
                $k = Students::getOsnovanie()[6];
                if (6==$model->osnovanie)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td>
                <?= $rasp_act_otch ? Html::a($rasp_act_otch->file->name,['download','id'=>$rasp_act_otch->id])  : 'Файл не загружен' ?>
            </td>
            <td>
                <?php
                $k = Students::getGracePeriod()[3];
                if (3==$model->grace_period)
                    echo "<p class='text-sm-center' > &#9745; {$k} </p>";
                else echo "<p class='text-sm-center'> &#9744; $k</p>";
                ?>
            </td>
            <td  style="text-align: center; vertical-align: middle;" ><p class="text-center">
                    <?= Yii::$app->getFormatter()->asDate($model->date_start_grace_period3) ?>
                    -
                    <?= Yii::$app->getFormatter()->asDate($model->date_end_grace_period3) ?>
                </p>
            </td>
            <td>
                <?= $rasp_act3 ? Html::a($rasp_act3->file->name,['download','id'=>$rasp_act3->id])  : 'Файл не загружен' ?>
            </td>


        </tr>
        </tbody>
    </table>

</div>

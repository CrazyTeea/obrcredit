<?php

use app\models\app\students\Students;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */
/* @var $form yii\widgets\ActiveForm */
$cans = User::$cans;
?>

<div class="students-form">

    <?php $readonly =  $cans[2] ? 1 : null;
    $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model,'name')->textInput(['readonly'=>$readonly]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model,'code')->textInput(['readonly'=>$readonly])?>
        </div>
    </div>

    <div style="display: none">
        <?= $form->field($model,'osnovanie')->radioList([
            ''
        ])->label(false) ?>
        <?= $form->field($model,'grace_period')->radioList([
            '',
        ])->label(false) ?>
    </div>




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
                <?= $form->field($model,'education_status')->radioList(['Не обучается','Обучается']) ?> <br>
                <?= date('d. M Y') ?> </td>
            <td rowspan="3"><p class="text-sm-center"> пункт 20 </p></td>
            <td rowspan="2">
                <p class="text-sm-center">пункт 2 части 2 статьи 61 Федерального закона
                    № 273-ФЗ
                    <?= $form->field($model,'osnovanie')->radioList([
                        1=>'отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по профессиональной образовательной программе обязанностей по добросовестному освоению такой образовательной программы и выполнению учебного плана',
                        2=>'установление нарушения порядка приема в образовательную организацию, повлекшего по вине обучающегося его незаконное зачисление в образовательную организацию',
                    ])->label(false) ?>
                </p>
            </td>
            <td rowspan="3">
                <?= $form->field($model,'rasp_act0')->fileInput()->label('Акт')//$rasp_act0 ?  $rasp_act0->file->name : 'Файл не загружен' ?>
            </td>
            <td rowspan="3">
                <?= $form->field($model,'grace_period')->radioList([
                    1=>'академический отпуск',
                ])->label(false) ?>
            </td>
            <td rowspan="5" style="text-align: center; vertical-align: middle;" >
                <p class="text-center">
                    <?= $form->field($model,'date_start_grace_period')->input('date')?>
                    -
                    <?= $form->field($model,'date_end_grace_period')->input('date')?>
                </p>
            </td>
            <td rowspan="3">
                <?= $form->field($model,'rasp_act1')->fileInput() ->label('Акт')//$rasp_act1 ?  $rasp_act1->file->name : 'Файл не загружен' ?>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>
                <?= $form->field($model,'osnovanie')->radioList([
                   3=> 'отчислен по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося',
                ])->label(false) ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 21 </p></td>
            <td><p class="text-sm-center">
                    перевод обучающегося для продолжения освоения основной профессиональной образовательной программы в другую образовательную организацию:
                    <?= $form->field($model,'osnovanie')->radioList([
                       4=> 'в связи с ликвидацией образовательной организации',
                        5=>'по независящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации',
                    ])->label(false) ?>
                </p>
            </td>
            <td>
                <?= $form->field($model,'dogovor')->fileInput()->label('Договор') //$dogovor ?  $dogovor->file->name : 'Файл не загружен' ?>
            </td>
            <td>
                <?= $form->field($model,'grace_period')->radioList([
                    2=>'отпуск по беременности и родам',
                ])->label(false) ?>
            </td>
            <td>
                <?= $form->field($model,'rasp_act2')->fileInput()->label('Акт')//$rasp_act2 ?  $rasp_act2->file->name : 'Файл не загружен' ?>
            </td>


        </tr>
        <tr>
            <td><p class="text-sm-center"> пункт 22 </p></td>
            <td>
                <?= $form->field($model,'osnovanie')->radioList([
                   6=> 'обучающимся (заемщиком) принято решение об отказе от продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации, в том числе в случае ликвидации образовательной организации'
                ])->label(false) ?>
            </td>
            <td>
                <?= $form->field($model,'rasp_act_otch')->fileInput()->label('Отчет')//$rasp_act_otch ?  $rasp_act_otch->file->name : 'Файл не загружен' ?>
            </td>
            <td>
                <?= $form->field($model,'grace_period')->radioList([
                    3=>'отпуск по уходу за ребенком по достижении им 3-х лет',
                ])->label(false) ?>
            </td>
            <td>
                <?= $form->field($model,'rasp_act3')->fileInput()->label('Акт')// $rasp_act3 ?  $rasp_act3->file->name : 'Файл не загружен' ?>
            </td>


        </tr>
        </tbody>
    </table>



    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

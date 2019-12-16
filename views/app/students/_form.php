<?php

use app\models\app\Banks;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\app\students\Students */
/* @var $form yii\widgets\ActiveForm */
$cans = Yii::$app->session['cans'];


function getDocByDescriptor(string $descriptor,array $docs, ActiveForm $form,\app\models\app\Files $file){

    foreach ($docs as $doc){
        if ($doc->descriptor == $descriptor){
             return $form->field($file,"[$doc->descriptor]file")->fileInput()->label($doc->label);
        }
    }
    return 'Дескриптор не найден';
}

?>

<div class="students-form">
    <?php $readonly =  $cans[2] ? 1 : null;
    $form = ActiveForm::begin();
   //$form->enableClientValidation=false;

    ?>
    <?=$form->errorSummary($model)?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model,'name')->textInput(['readonly'=>$readonly]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model,'code')->textInput()?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model,'date_credit')->textInput(['readonly'=>$readonly])?>
        </div>
    </div>
    <div class="row">
        <?php if (!$readonly): ?>
            <div class="col-md-3">
                <?=$form->field($model,'id_number_pp')->dropDownList( NumbersPp::getNumbersArray(),['readonly'=>$readonly])?>
            </div>
            <div class="col-md-3">
                <?=$form->field($model,'id_bank')->dropDownList( Banks::getBanksArray(),['readonly'=>$readonly])?>
            </div>
        <?php else:?>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="num_name" class="control-label">Номер ПП по образовательному кредиту</label>
                    <input type="text" readonly="" id="num_name" class="form-control" value="<?=$model->numberPP->number?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="num_name" class="control-label">Банк</label>
                    <input type="text" readonly="" id="num_name" class="form-control" value='<?=$model->bank->name?>'>
                </div>
            </div>

        <?php endif;?>
        <div class="col-md-3">
            <?=$form->field($model,'date_status')->input('date',['readonly'=>1])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model,'date_start')->input('date',['readonly'=>$readonly])?>
        </div>
    </div>
    <div style="display: none">
        <?= $form->field($model,'osnovanie')->radio([
            'label'=>'s','value'=>0,'uncheck'=>null
        ])->label(false) ?>
        <?= $form->field($model,'grace_period')->radio([
            'label'=>'s','value'=>0,'uncheck'=>null
        ])->label(false) ?>
    </div>

    <div class="panel panel-default" style="overflow: auto">

        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="4"><p class="text-center ">Статус обучающегося</p></th>
                <th colspan="4"><p class="text-center">Пролонгация льготного периода пользования <br> образовательным кредитом</p></th>
                <td rowspan="3"><p class="text-sm-center">Выпускник (завершено обучение в образовательной организации)</p></td>
            </tr>
            <tr>
                <td rowspan='2'><p class="text-sm-center">Продолжает <br> обучаться </p></td>
                <td colspan="3"><p class="text-sm-center">Досрочно прекратил образовательные отношения </p></td>
                <td rowspan='2'><p class="text-sm-center">Отсрочка льготного периода <br> в связи с предоставлением академического права  <br>
                        (пункт 12 часть 1 статья 34 Федерального закона № 273-ФЗ)</p>
                    <br> <button type="button" href="#clean2"> Сбросс</button>
                </td>
                <td rowspan='2'><p class="text-sm-center">Срок действия академического права</p> </td>
                <td rowspan='2'><p class="text-sm-center">Подтверждение
                        срока окончания академического права
                    </p></td>
                <td rowspan="2"><p class="text-sm-center">Переведен на бюджет</p></td>

            </tr>
            <tr>

                <td>Пункты постановления <br> Правительства РФ <br> от 26.02.2018 г. № 197</td>
                <td>Основание <br> <button type="button" href="#clean"> Сбросс</button></td>
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
                        <?= $form->field($model,'osnovanie')->radio([
                            'label'=>'отчисление как меры дисциплинарного взыскания, в случае невыполнения обучающимся по профессиональной образовательной программе обязанностей по добросовестному освоению такой образовательной программы и выполнению учебного плана',
                            'value'=>1,'uncheck'=>null])->label(false) ?>
                        <?= $form->field($model,'osnovanie')->radio([
                            'label'=>'установление нарушения порядка приема в образовательную организацию, повлекшего по вине обучающегося его незаконное зачисление в образовательную организацию',
                            'value'=>2,'uncheck'=>null])->label(false) ?>
                    </p>
                </td>
                <td rowspan="3">
                    <?= getDocByDescriptor('rasp_act0',$docTypes,$form,$file) ?>
                </td>
                <td rowspan="3">
                    <?= $form->field($model,'grace_period')->radio([
                        'label'=>'академический отпуск','value'=>1,'uncheck'=>null
                    ])->label(false) ?>

                </td>
                <td rowspan="3" style="text-align: center; vertical-align: middle;" >
                    <p class="text-center">
                        <?= $form->field($model,'date_start_grace_period1')->input('date')?>
                        -
                        <?= $form->field($model,'date_end_grace_period1')->input('date')?>
                    </p>
                </td>
                <td rowspan="3">
                    <?= getDocByDescriptor('rasp_act1',$docTypes,$form,$file) ?>
                </td>
                <td rowspan="6">
                    <?= $form->field($model,'perevod',['enableClientValidation' => false])->checkbox([],false)->label('Перевод на бюджет') ?>

                    <?= getDocByDescriptor('rasp_act4',$docTypes,$form,$file) ?>
                </td>
                <td rowspan="6" style="text-align: center;">
                    <?=$form->field($model,'isEnder',['enableClientValidation' => false])->checkbox([],false)?>
                    <?=$form->field($model,'date_ender',['enableClientValidation' => false])->input('date'); ?>
                    <?= getDocByDescriptor('ender',$docTypes,$form,$file) ?>
                </td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td>
                    <?= $form->field($model,'osnovanie')->radio([
                        'label'=> 'отчислен по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося',
                        'value'=>3,'uncheck'=>null])->label(false) ?>
                </td>
            </tr>
            <tr>
                <td><p class="text-sm-center"> пункт 21 </p></td>
                <td><p class="text-sm-center">
                        перевод обучающегося для продолжения освоения основной профессиональной образовательной программы в другую образовательную организацию:
                        <?= $form->field($model,'osnovanie')->radio([
                            'label'=>  'в связи с ликвидацией образовательной организации',
                            'value'=>4,'uncheck'=>null])->label(false) ?>
                        <?= $form->field($model,'osnovanie')->radio([
                            'label'=> 'по независящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации',
                            'value'=>5,'uncheck'=>null])->label(false) ?>
                    </p>
                </td>
                <td>
                    <?= getDocByDescriptor('dogovor',$docTypes,$form,$file) ?>
                </td>
                <td>
                    <?= $form->field($model,'grace_period')->radio([
                        'label'=>'отпуск по беременности и родам','value'=>2,'uncheck'=>null
                    ])->label(false) ?>
                </td>
                <td>
                    <p class="text-center">
                        <?= $form->field($model,'date_start_grace_period2')->input('date')?>
                        -
                        <?= $form->field($model,'date_end_grace_period2')->input('date')?>
                    </p>
                </td>
                <td>
                    <?= getDocByDescriptor('rasp_act2',$docTypes,$form,$file) ?>
                </td>


            </tr>
            <tr>
                <td><p class="text-sm-center"> пункт 22 </p></td>
                <td>
                    <?= $form->field($model,'osnovanie')->radio([
                        'label'=>   'обучающимся (заемщиком) принято решение об отказе от продолжения обучения, по обстоятельствам, не зависящим от воли обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося и образовательной организации, в том числе в случае ликвидации образовательной организации',
                        'value'=>6,'uncheck'=>null])->label(false) ?>
                </td>
                <td>
                    <?= getDocByDescriptor('rasp_act_otch',$docTypes,$form,$file) ?>
                </td>
                <td>
                    <?= $form->field($model,'grace_period')->radio([
                        'label'=>'отпуск по уходу за ребенком по достижении им 3-х лет','value'=>3,'uncheck'=>null
                    ])->label(false) ?>
                </td>
                <td>
                    <p class="text-center">
                        <?= $form->field($model,'date_start_grace_period3')->input('date')?>
                        -
                        <?= $form->field($model,'date_end_grace_period3')->input('date')?>
                    </p>
                </td>
                <td>
                    <?= getDocByDescriptor('rasp_act3',$docTypes,$form,$file) ?>
                </td>


            </tr>
            </tbody>
        </table>
    </div>






    <div class="form-group btn-group">
        <?= Html::a('Назад',['view','id'=>$model->id],['class'=>'btn btn-primary'])?>
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

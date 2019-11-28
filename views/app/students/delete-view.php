<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>


<?php Pjax::begin()?>
<?php $form = ActiveForm::begin(['method'=>'get']);?>



    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Фильтр
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3"><?=$form->field($model,'name')?></div>
                        <div class="col-md-3"><?=$form->field($model,'code')?></div>
                        <div class="col-md-3"><?=$form->field($model,'id_number_pp')->widget(Select2::class,['data'=>$nums,'pluginOptions'=>['multiple'=>true]])?></div>
                        <div class="col-md-3"><?=$form->field($model,'id_bank')->widget(Select2::class,['data'=>$banks,'pluginOptions'=>['multiple'=>true]])?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8"><?=$form->field($model,'id_org')->widget(Select2::class,['data'=>$orgs,'pluginOptions'=>['multiple'=>true]])?></div>
                        <div class="col-md-4"><?=$form->field($model,'date_start')->input('date')?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <?=Html::submitButton('Отфильтровать',['class'=>'btn btn-success']) ?>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?php ActiveForm::end();?>


<?= GridView::widget(['dataProvider'=>$provider,'columns'=>
    [
            'name','code','organization.name','numberPP.number','bank.name','date_start:date',
        ['class'=>\yii\grid\ActionColumn::class]
        ]
]) ?>

<?php Pjax::end() ?>

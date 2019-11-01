<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Информация';
$this->params['breadcrumbs'][] = ['label' => 'Все заявления', 'url'=> Url::home()];
?>
<div class="site-about">
    <h4><?= Html::encode($this->title) ?></h4>
    <h5>Рекомендованные интернет - браузеры:</h5>
    <p><a href="http://www.opera.com/ru/computer/windows" target="_blank">Opera</a></p>
    <p><a href="https://www.google.ru/chrome/browser/desktop/" target="_blank">Google Chrome</a></p>
    <p><a href="https://browser.yandex.ru/new/desktop/main/" target="_blank">Яндекс браузер</a></p>
    <hr>
    <h5>Техническая поддержка:</h5>
    <p>8-495-989-84-47 (многоканальный)</p>
    <p>8(964)527-30-83</p>
    <p><a href="mailto:ias@mirea.ru">ias@mirea.ru</a></p>
    <!--
    <h5>Инструкция</h5>
    <?= Html::a('Инструкция',Yii::getAlias('@web').'/downloads/manual.pdf')?>
    <br>
    <?= Html::a('Заявление на замену денежной компенсацией части отпуска',Yii::getAlias('@web').'/downloads/Заявление на замену денежной компенсацией части отпуска.docx')?>
    <br>
    <?= Html::a('Заявление на командировку',Yii::getAlias('@web').'/downloads/Заявление на командировку.docx')?>
    <br>
    <?= Html::a('Заявление на отпуск',Yii::getAlias('@web').'/downloads/Заявление на отпуск.docx')?>
    <br>
    <?= Html::a('Уведомление о командировке (РФ)',Yii::getAlias('@web').'/downloads/Уведомление о командировке (РФ).docx')?>
    <br>
    <br>
    <?= Html::a('Характеристика образец бланка',Yii::getAlias('@web').'/downloads/Харктеристика образец бланка.doc')?>
    <br>
    <br>
    <?= Html::a('Характеристика образец заполнения',Yii::getAlias('@web').'/downloads/Харктеристика образец заполнения.doc')?>
    <br>
    -->
</div>

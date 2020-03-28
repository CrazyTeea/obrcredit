<?php

use app\models\app\students\StudentsHistorySearch;
use yii\grid\GridView;
use yii\widgets\Pjax;

$dataProvider = $views['keks']['provider'];
$searchModel = $views['keks']['search'];
?>

<?php Pjax::begin(['enableReplaceState'=>false,'enablePushState'=>false,'timeout'=>5000]); ?>
<?php //= $this->render('_search', ['model' => $searchModel,'changes'=>$changes]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => StudentsHistorySearch::getColumns(),
]); ?>


<?php Pjax::end(); ?>
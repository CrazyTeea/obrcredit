<?php
namespace app\controllers\app;

use app\models\User;
use Yii;
use yii\web\Controller;

abstract class AppController extends Controller
{

    public function __construct($id, $module, $config = [])
    {
        if (!Yii::$app->session['cans'])
            Yii::$app->session['cans']=[
                Yii::$app->getUser()->can('root'),
                Yii::$app->getUser()->can('admin'),
                Yii::$app->getUser()->can('podved')
            ];
        parent::__construct($id, $module, $config);
    }
}
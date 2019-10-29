<?php
namespace app\controllers\app;

use app\models\User;
use Yii;
use yii\web\Controller;

abstract class AppController extends Controller
{
    protected $cans;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        if (!Yii::$app->session['cans'])
            Yii::$app->session['cans']=[
                Yii::$app->getUser()->can('root'),
                Yii::$app->getUser()->can('admin'),
                Yii::$app->getUser()->can('podved')
            ];
        if (!Yii::$app->user->getIsGuest()) {
            Yii::$app->session[ 'id_org' ] = User::findOne( Yii::$app->user->id )->id_org;
        }
        $this->cans = Yii::$app->session['cans'];
    }
}
<?php
namespace app\controllers\app;

use app\models\RouteHistory;
use app\models\User;
use Yii;
use yii\web\Controller;

abstract class AppController extends Controller
{
    protected $cans;

    protected $_user;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        if (!Yii::$app->session->get('cans'))
            Yii::$app->session->set('cans',[
                Yii::$app->getUser()->can('root'),
                Yii::$app->getUser()->can('admin'),
                Yii::$app->getUser()->can('podved')
            ]);
        if (!Yii::$app->user->isGuest) {
            $this->_user = User::findOne( Yii::$app->user->id );
            Yii::$app->session->set( 'id_org', $this->_user->id_org);
        }
        $this->cans = Yii::$app->session->get('cans');
    }

    public function updateRouteHistory(string $route){
        if (Yii::$app->session->get('route') != $route)
            Yii::$app->session->set('route', $route);
        $routeModel = RouteHistory::findOne(['id_user'=>$this->_user->id,'route'=>$route]);
        if (!$routeModel) {
            $routeModel = new RouteHistory();
            $routeModel->id_user = $this->_user->id;
            $routeModel->route = $route;
        }
        $routeModel->count++;
        $routeModel->save();
    }
}
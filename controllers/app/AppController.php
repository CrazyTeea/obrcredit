<?php
namespace app\controllers\app;

use app\models\User;
use Yii;
use yii\web\Controller;

abstract class AppController extends Controller
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }
}
<?php
namespace app\controllers\app;

use yii\web\Controller;

abstract class AppController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }
}
<?php


namespace app\controllers\app;


use app\models\Import;
use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\UploadedFile;

class ImportController extends AppController
{
    public function actionIndex(){
        $model = new Import();
        $res = ['error'=>'kek'];

        if (!Yii::$app->request->getIsAjax())
            return $this->render('index',compact('model'));

        if (Yii::$app->request->getIsPost()) {
            $model->_csv = UploadedFile::getInstance($model, '_csv');
            if ($model->validate())
                return Json::encode(['error'=>null]);
            throw new \Exception('errror');
        }


    }
}
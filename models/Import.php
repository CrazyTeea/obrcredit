<?php


namespace app\models;


use yii\base\Model;
use yii\web\UploadedFile;

class Import extends Model
{
    public $_csv;
    public $csv_inst;
    public function beforeValidate()
    {

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function rules()
    {
        return [
          [['_csv'],'file', 'skipOnEmpty' => false,'extensions'=>['csv'],'checkExtensionByMimeType'=>false,],
        ];
    }
}
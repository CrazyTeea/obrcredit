<?php

namespace app\models\app;

use app\models\app\students\Students;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property int $size
 * @property string $updated_at
 * @property string $created_at
 * @property int $system_status
 */
class Files extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'],'file'],
            [['size', 'system_status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['name', 'extension'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'extension' => 'Extension',
            'size' => 'Size',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'system_status' => 'System Status',
        ];
    }

    /**
     * @param UploadedFile $instance
     * @param Students $student
     * @return int|null
     * @throws \yii\base\Exception
     */
    public function upload( UploadedFile $instance, Students $student){
        $path = Yii::getAlias('@webroot')."./uploads/$student->id_org/$student->id";
        FileHelper::createDirectory($path);
        $this->name = $instance->baseName;
        $this->extension = $instance->extension;
        $this->size = $instance->size;
        if ($instance->saveAs("$path/$instance->baseName.$instance->extension") and $this->save())
            return $this->id;
        return null;
    }

    public function generateLink($id_org,$id_st){
        $path = Yii::getAlias( '@web' ) . "/uploads/$id_org/$id_st";
        $path .= "/$this->name.$this->extension";
        return $path;
    }
}

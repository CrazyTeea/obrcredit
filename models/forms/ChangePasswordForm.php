<?php


namespace app\models\forms;


use app\models\User;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $username;
    public $password;
    public function rules()
    {
        return [
            [['password','username'],'string'],
            [['password'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
          'password'=>'новый пароль',
          'username'=>'логин'
        ];
    }
    public function change_password()
    {
        if (!$this->validate())
            return -1;
        $user = User::findOne(['username'=>$this->username]);
        if (!$user)
            return -1;
        $user->setPassword($this->password);
        $user->updated_at = time();
        if ($user->save())
            return 1;
        return -1;
    }
}
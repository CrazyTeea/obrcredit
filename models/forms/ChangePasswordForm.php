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
            return null;
        $user = User::findOne(['username'=>$this->username]);
        if (!$user)
            return null;
        $user->setPassword($this->password);
        $user->updated_at = time();
        if ($user->save())
            return $user;
        return null;
    }
}
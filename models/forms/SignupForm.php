<?php


namespace app\models\forms;


use app\models\User;
use Yii;
use yii\base\Model;
use yii\rbac\PhpManager;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $name;
    public $id_org;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','password','name'],'string'],
            [['username','password','name'],'required'],
            [['id_org'],'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'логин',
            'name'=>'имя',
            'password'=>'пароль',
            'id_org'=>'организация',
        ];
    }

    /**
     * @return bool|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $user->username = $this->username;
        $user->name = $this->name;
        $user->updated_at = $user->created_at = time();
        $user->status = User::STATUS_ACTIVE;
        $user->id_org=$this->id_org;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save()) {
            $auth = new PhpManager();
            $role = $auth->getRole('podved');
            if ($role)
                return $auth->assign($role, $user->id);
        }
        return false;
    }

}
<?php


namespace app\commands;


use app\models\app\Banks;
use app\models\app\Organizations;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;
use app\models\UserConsole as User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\rbac\PhpManager;

class ReferenceController extends Controller
{
    static $jwt_key = 'example_key233';
    public $error_message;
    public function actionIndex(){
        $transaction = Yii::$app->db->beginTransaction();

        if( $this->actionOrganization()){
            $transaction->commit();
            echo "success\n";
        }else{
            $transaction->rollBack();
            echo "not success\n";
            exit;
        }

        return ExitCode::OK;
    }
    public function actionAddStartUsers(){
        $admin = (User::findOne(['username'=>'admin@admin.ru'])) ? User::findOne(['username'=>'admin@admin.ru']) : new User();
        $root = (User::findOne(['username'=>'root@admin.ru'])) ? User::findOne(['username'=>'root@admin.ru']) : new User();
        $user = (User::findOne(['username'=>'user@admin.ru'])) ? User::findOne(['username'=>'user@admin.ru']) : new User();
        $root->email = $root->username = 'root@admin.ru';
        $admin->username = $admin->email = 'admin@admin.ru';
        $user->username = $user->email = 'user@admin.ru';
        $user->status= $admin->status=$root->status = User::STATUS_ACTIVE;
        $user->updated_at=$user->created_at=
        $admin->updated_at=$admin->created_at=
        $root->updated_at=$root->created_at=time();
        $user->id_org = 100;
        $root->setPassword('password');
        $admin->setPassword('password');
        $user->setPassword('password');
        $root->generateAuthKey();
        $admin->generateAuthKey();
        $user->generateAuthKey();
        echo 'root=>'.$root->save();
        echo 'admin=>'.$admin->save();
        echo 'user=>'.$user->save();

        $pm = new PhpManager();

        $rootr = $pm->getRole('root');
        $adminr = $pm->getRole('admin');
        $userr= $pm->getRole('podved');
        $pm->revokeAll($root->id);
        $pm->revokeAll($admin->id);
        $pm->revokeAll($user->id);
        $pm->assign($rootr,$root->id);
        $pm->assign($adminr,$admin->id);
        $pm->assign($userr,$user->id);


    }


    public function actionOrganization()
    {
        echo "Выполняется синхронизация организаций\n";
        $err = 0;
        $err_data = null;
        $signer = new Sha256();
        $key = new Key(self::$jwt_key);
        $token = (new Builder())->withClaim('reference', 'organization')
           // ->sign($signer, self::$jwt_key)
            ->getToken($signer,$key);
        $response_token = file_get_contents("http://api.xn--80apneeq.xn--p1ai/api.php?option=reference_api&action=get_reference&module=constructor&reference_token=$token");
        $signer = new Sha256();
        $token = (new Parser())->parse($response_token);
        if($token->verify($signer, self::$jwt_key)) {

            $data_reference = $token->getClaims();
          //  $this->model_name = Organizations::className();
            foreach ($data_reference AS $key=>$data){
                $row_org = Organizations::findOne($data->getValue()->id);
                if(empty($row_org)) {
                    echo 'kek';
                    $row_org = new Organizations();
                    $row_org->id = $data->getValue()->id;
                }
                $row_org->full_name = htmlspecialchars_decode($data->getValue()->fullname);
                $row_org->short_name =htmlspecialchars_decode( $data->getValue()->shot_name);
                $row_org->name = htmlspecialchars_decode($data->getValue()->name);
                $row_org->system_status = ($data->getValue()->status_org==1
                    and $data->getValue()->system_status==1
                    and $data->getValue()->region_id !=0
                    and $data->getValue()->subordination == 1
                ) ? 1 : 0;
                if(!$row_org->save())
                    var_dump($row_org->errors);

            }
            return true;
        }
        else
            return false;


    }
    public function actionAddStudents(){
        $csv = Yii::getAlias('@webroot')."/toParse/students.csv";
        $csv = fopen($csv,'r');
        $r=0;
        $orgs = Organizations::findAll(['system_status'=>1]);
        foreach ($orgs as $org) {
            $org->system_status = 0;
            $org->save();
        }
        while (($row = fgetcsv($csv,1000,';')) != false){
            $r++;
            if ($r==1)
                continue;
            var_dump(explode(' ',$row[4])[1]);
            $bank = Banks::find()->where(['like','name',explode(' ',$row[4])[1]])->one();
            $number = NumbersPp::find()->where(['like','number',$row[5]])->one();
            $student = Students::findOne(['name'=>$row[1],'code'=>$row[2]]);
            if (!$student) {
                $student = new Students();
                $student->education_status = 1;
            }
            $student->status = 1;
            $student->name = $row[1];
            $student->code = $row[2];
            $student->date_credit = $row[3];
            $student->id_org = $row[0];
            $student->id_bank = $bank ? $bank->id : 0;
            $student->id_number_pp = $number ? $number->id : 0;
            $org = Organizations::findOne(['id'=>$student->id_org]);
            if (!$org)
                continue;
            $org->system_status = 1;
            $org->save();
            $student->save();
        }

    }

}
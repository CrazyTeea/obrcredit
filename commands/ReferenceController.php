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
    public function actionAddStudents($file,$nameId,$codeId,$dCreditId,$orgId,$numPP,$bankId){


        $csv = Yii::getAlias('@webroot')."/toParse/$file.csv";
        $csv = fopen($csv,'r');

        while (($row = fgetcsv($csv,1000,';')) != false){

           echo "name => $row[$nameId] code=> $row[$codeId] dateCredit=>$row[$dCreditId] org=>$row[$orgId]\n";
            $bank = Banks::find()->where(['like','name',explode(' ',$row[$bankId])[1]])->one();

            $number = NumbersPp::find()->where(['like','number',$row[$numPP]])->one();

            $student = Students::findOne(['name'=>$row[$nameId],'code'=>$row[$codeId]]);

            if (!$student) {
                $student = new Students();
                $student->education_status = 1;
                $student->date_create = date('Y-m-d');
            }
           // echo "st1";
            $student->status = 1;
            $student->name = $row[$nameId];
            $student->code = $row[$codeId];
            $student->date_credit = $row[$dCreditId];
            $student->id_org = $row[$orgId];
            $students = Students::findAll(['id_org'=>$student->id_org]);
            foreach ($students as $st){
                $st->status = 1;
                $st->save();
            }
            $student->id_bank = $bank ? $bank->id : 0;
            $student->id_number_pp = $number ? $number->id : 0;

            if ($student->save()){
                $org = Organizations::findOne($student->id_org);
                if ($org){
                    $org->system_status = 1;
                    $org->save();
                }
            }
        }
        return "success!";

    }
    public function actionUsers($file,$orgId,$emailId){
        $mailer = Yii::$app->getMailer();


        $csv = Yii::getAlias('@webroot')."/toParse/$file.csv";
        $csv = fopen($csv,'r');

        while (($row = fgetcsv($csv,1000,';')) != false){


            $user = User::findOne(['username'=>$row[$emailId]]);
             if ($user)
                 continue;
            $user = new User();

            $user->status = 10;
            $login = $user->email = $user->username = $row[$emailId];
            $password = Yii::$app->security->generateRandomString(6);
            $user->setPassword($password);
            $user->generatePasswordResetToken();
            $user->generateAuthKey();
            $user->updated_at = $user->created_at = time();
            $user->id_org=$row[$orgId];
            if ($user->save()) {
                $auth = new PhpManager();
                $auth->revokeAll( $user->id );
                $auth->assign( $auth->getRole( 'podved' ), $user->id );


                $mailer->compose()
                    ->setTo( $user->email )
                    ->setFrom( 'ias@mirea.ru' )
                    ->setSubject( 'Письмо от 18.09.2019 № МН-1323/СК - Мониторинг образовательного кредитования' )
                    ->setTextBody( "Уважаемые коллеги! Направляем Вам данные для входа в модуль \"Мониторинг образовательного кредитования\".\n Вход в модуль по адрессу обркредит.иасмон.рф:\n
                    Логин: $login  \n Пароль: $password \n" )
                    ->send();
                echo "$row[1] $row[3] $row[7] $password\n";
            }

        }

       /* $user = new User();
        $user->status = 10;
        $login = $user->email = $user->username = 'email@email.ru';
        $password = "password";
        $user->setPassword($password);
        $user->generatePasswordResetToken();
        $user->generateAuthKey();
        $user->updated_at = $user->created_at = time();
        $user->id_org=100;
        $user->save();
        $auth = new PhpManager();
        $auth->assign($auth->getRole('podved'),$user->id);

        $mailer->compose()
            ->setTo('lipatow.nikita@yandex.ru')
            ->setFrom('ias@mirea.ru')
            ->setSubject('Письмо от 18.09.2019 № МН-1323/СК - Мониторинг образовательного кредитования')
            ->setTextBody("Уважаемые коллеги! Направляем Вам данные для входа в модуль \"Мониторинг образовательного кредитования\". Вход в модуль по адрессу обркредит.иасмон.рф: $login:$password")
            ->send();
        //*/
    }
    public function actionEmail(){
        $users = User::find()->all();
        $email = "
        Предоставление образовательных кредитов банками и иными кредитными организациями для обучающихся осуществляется с учетом выполнения требований, указанных в пункте 4 Постановления, исполнение которых обеспечивает предоставление субсидии банку и иной кредитной организации.

Вместе с тем, обращаем внимание, что случае досрочного прекращения образовательных отношений между обучающемся (заемщиком) и образовательной организацией в соответствии с пунктом 2 части 2 статьи 61 Федерального закона «Об образовании в Российской Федерации», а также по инициативе обучающегося или родителей (законных представителей) несовершеннолетнего обучающегося образовательная организация в течение 10 рабочих дней со дня издания распорядительного акта об отчислении обучающегося (заемщика) обязана проинформировать Министерство, банк и иную кредитную организацию об отчислении обучающегося (заемщика),
с приложением копии распорядительного акта (пункт 20 Постановления).

Согласно пункту 19 Постановления Министерство ежемесячно информирует образовательные организации о получении обучающимися образовательных услуг за счет средств образовательного кредита.

Во исполнение указанного пункта Министерство направляет список обучающихся, воспользовавшихся образовательным кредитом
с государственной поддержкой с которым следует ознакомиться
в информационно-аналитической системе «Мониторинг» Минобрнауки России (https://обркредит.иасмон.рф).

В целях обеспечения исполнения вышеуказанных пунктов Постановления Министерство просит в срок до 14 октября 2019 г. разместить информацию по текущим статусам студентов за отчетный период - сентябрь 2019 года в разработанной форме в ИАС Мониторинг.

Кроме того, необходимо обратить внимание на необходимость учета обучающихся (заемщиков) в указанной информационной системе, воспользовавшихся академическим правом в соответствии с пунктом 12 части 1 статьи 34 Федерального закона «Об образовании в Российской Федерации».

Вместе с тем, абзац подпункта «а» пункта 4 Постановления предусматривает право обучающегося (заемщика) на однократную пролонгацию договора о предоставлении образовательного кредита в случае освоения им других основных образовательных услуг. Пролонгация проводится путем заключения дополнительного соглашения к договору о предоставлении образовательного кредита.
        
        ";
        $mailer = Yii::$app->getMailer();
        foreach ($users as $user)
        {
            $mailer->compose()
                ->setTo( $user->email )
                ->setFrom( 'ias@mirea.ru' )
                ->setSubject( 'Мониторинг образовательного кредитования' )
                ->setTextBody( $email )
                ->send();
        }
    }

}
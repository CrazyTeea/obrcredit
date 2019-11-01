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
                $student = Students::findOne(['id_org'=>$row_org->id]);
                $row_org->system_status = ($student) ? 1 : 0;
                if(!$row_org->save())
                    var_dump($row_org->errors);

            }
            return true;
        }
        else
            return false;


    }
    public function actionStudents($file,$nameId,$codeId,$dCreditId,$orgId,$numPP,$bankId,$dStart){

        $csv = Yii::getAlias('@webroot')."/toParse/$file.csv";
        $csv = fopen($csv,'r');

        while (($row = fgetcsv($csv,1000,';')) != false){
            $student = Students::findOne(['name'=>$row[$nameId],'code'=>$row[$codeId],'date_credit'=>$row[$dCreditId]]);
            if ($student){
                $student->date_start = $row[$dStart];
            }else {
                $student = new Students();
                $student->name = $row[$nameId];
                $student->code = $row[$codeId];
                $student->date_credit = $row[$dCreditId];
                $org  = Organizations::findOne($row[$orgId]);
                if ($org)
                    $student->id_org = $row[$orgId];
            }
            $n = NumbersPp::findOne(['number'=>$row[$numPP]]);
            $b = Banks::findOne(['name'=>$row[$bankId]]);
            if ($n)
                $student->id_number_pp = $n->id;
            if ($b)
                $student->id_bank = $b->id;

            $student->save();
        }
        return "success!";

    }
    public function actionUsers($file,$orgId,$emailId,$nameID){
        $mailer = Yii::$app->getMailer();


        $csv = Yii::getAlias('@webroot')."/toParse/$file.csv";
        $csv = fopen($csv,'r');

        while (($row = fgetcsv($csv,1000,';')) != false){

            $email = preg_replace('/\s/', '', $row[ $emailId ]);
            $user = User::findOne(['username'=>$email]);
             if ($user)
                 continue;
             try {
                 $user = new User();

                 $user->status = 10;
                 $login = $user->email = $user->username = $email;
                 $password = Yii::$app->security->generateRandomString( 6 );
                 $user->setPassword( $password );
                 $user->generatePasswordResetToken();
                 $user->generateAuthKey();
                 $user->updated_at = $user->created_at = time();
                 $user->id_org = preg_replace('/\s/', '', $row[ $orgId ]);
                 $user->name =$row[$nameID];
                 if ( $user->save() ) {
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
                     echo "$row[$orgId] $row[$nameID] $row[$emailId] $password\n";
                 }
             }catch (\Exception $e){echo $e->getMessage(); echo "\n$user->email";}

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
        foreach ($users as $i =>$user)
        {
            if ($i<113)
                continue;
            try {
                $mailer->compose()
                    ->setTo( $user->email )
                    ->setFrom( 'ias@mirea.ru' )
                    ->setSubject( 'Мониторинг образовательного кредитования' )
                    ->setTextBody( $email )
                    ->send();
            }
            catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

}
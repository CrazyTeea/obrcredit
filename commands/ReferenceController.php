<?php


namespace app\commands;


use app\models\app\Banks;
use app\models\app\Organizations;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;
use app\models\app\students\StudentsHistory;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;


class ReferenceController extends Controller
{
    static $jwt_key = 'example_key233';
    public $error_message;

    public function actionIndex()
    {
        $transaction = Yii::$app->db->beginTransaction();

        if ($this->actionOrganization()) {
            $transaction->commit();
            echo "success\n";
        } else {
            $transaction->rollBack();
            echo "not success\n";
            exit;
        }

        return ExitCode::OK;
    }

    public function actionOrganization()
    {
        echo "Выполняется синхронизация организаций\n";

        $signer = new Sha256();
        $key = new Key(self::$jwt_key);
        $token = (new Builder())->withClaim('reference', 'organization')
            // ->sign($signer, self::$jwt_key)
            ->getToken($signer, $key);
        $response_token = file_get_contents("http://api.xn--80apneeq.xn--p1ai/api.php?option=reference_api&action=get_reference&module=constructor&reference_token=$token");
        $signer = new Sha256();
        $token = (new Parser())->parse($response_token);
        if ($token->verify($signer, self::$jwt_key)) {

            $data_reference = $token->getClaims();
            foreach ($data_reference as $key => $data) {
                $row_org = Organizations::findOne($data->getValue()->id);
                if (empty($row_org)) {
                    $row_org = new Organizations();
                    $row_org->id = $data->getValue()->id;
                }
                $row_org->full_name = htmlspecialchars_decode($data->getValue()->fullname);
                $row_org->short_name = htmlspecialchars_decode($data->getValue()->shot_name);
                $row_org->name = htmlspecialchars_decode($data->getValue()->name);
                $row_org->system_status = (Students::findOne(['id_org' => $row_org->id])) ? 1 : 0;
                $row_org->save();

            }
            return true;
        } else
            return false;


    }

    public function actionSt($nameId, $perevod, $idOrgId, $statusId, $gpId, $esPP, $osnId, $ender, $numpp, $bank)
    {
        $csvP = Yii::getAlias('@webroot') . "/toParse/old.csv";
        $csv = fopen($csvP, 'r');

        if (!$csvP)
            exit("Файл не найден");

        $row = fgetcsv($csv, 1000, ';');

        echo "
            name->$row[$nameId]
            id_org->$row[$idOrgId]
            status кредита->$row[$statusId]
            perevod->$row[$perevod]
            gp-> $row[$gpId]
            es->$row[$esPP]
            osn->$row[$osnId]
            ender->$row[$ender]
            numpp->$row[$numpp]
            bank ->$row[$bank] \n";


        fclose($csv);
        $csv = fopen($csvP, 'r');
        echo "Вы уверене? \n ";
        $key = readline();
        if (!($key === "yes" || $key === "y" || $key === "Y")) {
            exit(0);
        }


        while (($row = fgetcsv($csv, 32000, ';')) != false) {
            $name = mb_convert_case($row[$nameId], MB_CASE_TITLE);
            $student = Students::find()->where(['name' => $name, 'id_org' => $row[$idOrgId], 'id_number_pp' => $row[$numpp], 'id_bank' => $row[$bank], 'year(date_start)' => 2017])->one();

            if ($student) {
                $student->grace_period = $row[$gpId];
                $student->education_status = $row[$esPP];
                $student->osnovanie = $row[$osnId];
                $student->isEnder = $row[$ender];
                $student->status = $row[$statusId];
                $student->perevod = $row[$perevod];
                $student->save(false);
            }
        }
    }

    public function actionStudents($file, $nameId, $dCreditId, $codeId, $orgId, $numPP, $bankId, $dStart)
    {

        $csvP = Yii::getAlias('@webroot') . "/toParse/$file.csv";

        $csv = fopen($csvP, 'r');
        if (!$csvP)
            exit("Файл не найден");

        $row = fgetcsv($csv, 1000, ';');
        $num = NumbersPp::findOne($row[$numPP]);
        if (!$num) {
            echo "пп не верный $row[$numPP]";
            exit(-1);
        }
        $num = $num->number;
        $bank = Banks::findOne($row[$bankId]);
        if (!$bank) {
            echo "банк не верный $row[$bankId]";
            exit(-1);
        }
        $bank = $bank->name;
        echo "
            Организация->$row[$orgId]
            ФИО->$row[$nameId]
            Дата кредита->$row[$dCreditId]
            пп-> $num
            банк->$bank
            дата начала обуч->$row[$dStart]  \n";


        fclose($csv);
        $csv = fopen($csvP, 'r');
        echo "Вы уверене? \n ";
        $key = readline();
        if (!($key === "yes" || $key === "y" || $key === "Y")) {
            exit(0);
        }
        $countVip = 0;
        $countOtch = 0;
        $count = 0;
        $year = date('Y', strtotime($row[$dStart]));
        $month = date('m', strtotime($row[$dStart])) - 1;

        if ($month < 1) {
            $month = 12;
            $year--;
        }

        while (($row = fgetcsv($csv, 32000, ';')) != false) {
            $name = mb_convert_case($row[$nameId], MB_CASE_TITLE);

            $student2 = Students::find()
                ->where(['name' => $name, 'date_credit' => $row[$dCreditId],
                    'YEAR(date_start)' => date('Y', strtotime($row[$dStart])),
                    'MONTH(date_start)' => date('m', strtotime($row[$dStart]))])->one();
            if ($student2) continue;
            $student2 = Students::find()->where(['name' => $name, 'date_credit' => $row[$dCreditId], 'YEAR(date_start)' => $year, 'MONTH(date_start)' => $month])->one();
            $student = new Students();
            if ($student2 and $student2->isEnder) {
                $student->education_status = 0;
                $student->isEnder = 1;
                $student->osnovanie = 0;
                $student->grace_period = 0;
                $student->date_ender = $student2->date_ender;
            } elseif ($student2 and !$student2->education_status and !$student2->isEnder) {
                $student->education_status = 0;
                $student->osnovanie = $student2->osnovanie;
                $student->isEnder = 0;
                $student->grace_period = 0;
            } else $student->education_status = 1;


            $student->code = $row[$codeId];
            $student->grace_period = $student->perevod = $student->osnovanie = $student->isEnder = 0;
            $student->date_start = $row[$dStart];
            $student->name = $name;
            $student->date_credit = $row[$dCreditId];
            $student->id_org = $row[$orgId];
            $student->date_create = date("Y-m-d");
            $student->status = 1;
            $student->id_number_pp = $row[$numPP];
            $student->id_bank = $row[$bankId];


            if ($student->save(false)) {
                $count++;
                $org = Organizations::findOne($student->id_org);
                if ($org) {
                    $org->system_status = 1;
                    $org->save(false);
                }
                echo "
            Организация-$student->id_org
            ФИО->$student->name
            КОД->$student->code
            Дата кредита-> $student->date_credit
            номер пп-> $student->id_number_pp
            нмоер банка->$student->id_bank
            дата начала обуч-> $student->date_start  \n";

                if ($student2 and !$student2->system_status) {
                    $student->system_status = 0;
                    $sh = StudentsHistory::findOne(['id_student' => Students::find()->select('id')->where(['name' => $student->name, 'date_credit' => $student->date_credit])->column()]);
                    if ($sh) {
                        $sh = new StudentsHistory();
                        $sh->id_student = $student->id;
                        $sh->id_change = 1;
                        $sh->save();
                    }
                    $student->save();
                }
            }

        }
        echo "добавлено студентов $count \n отчислены в прошлом месяце $countOtch \n выпускники в прошлом месяце $countVip \n";


        fclose($csv);
        echo "success!";
    }

    public function actionEnder()
    {
        $sts = Students::find()->where(['YEAR(date_start)'=>2017])->all();
        foreach ($sts as $st){
            if (($temp = Students::find()->where(['id_org'=>$st->id_org,'name'=>$st->name,'id_bank'=>$st->id_bank,'id_number_pp'=>$st->id_number_pp,'year(date_start)'=>2018])->one()) && $temp->date_ender){
                $st->date_ender = $temp->date_ender;
                $st->isEnder = true;
                $st->education_status=$st->osnovanie=$st->grace_period = 0;
                $st->save(false);
            }
        }
    }


}
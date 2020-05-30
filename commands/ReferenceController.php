<?php


namespace app\commands;


use app\models\app\Banks;
use app\models\app\Organizations;
use app\models\app\students\NumbersPp;
use app\models\app\students\Students;

use app\models\app\students\StudentsHistory;
use app\models\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

        if ( $this->actionOrganization() ) {
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
        $key = new Key( self::$jwt_key );
        $token = ( new Builder() )->withClaim( 'reference', 'organization' )
            // ->sign($signer, self::$jwt_key)
            ->getToken( $signer, $key );
        $response_token = file_get_contents( "http://api.xn--80apneeq.xn--p1ai/api.php?option=reference_api&action=get_reference&module=constructor&reference_token=$token" );
        $signer = new Sha256();
        $token = ( new Parser() )->parse( $response_token );
        if ( $token->verify( $signer, self::$jwt_key ) ) {

            $data_reference = $token->getClaims();
            foreach ($data_reference AS $key => $data) {
                $row_org = Organizations::findOne( $data->getValue()->id );
                if ( empty( $row_org ) ) {
                    $row_org = new Organizations();
                    $row_org->id = $data->getValue()->id;
                }
                $row_org->full_name = htmlspecialchars_decode( $data->getValue()->fullname );
                $row_org->short_name = htmlspecialchars_decode( $data->getValue()->shot_name );
                $row_org->name = htmlspecialchars_decode( $data->getValue()->name );
                $student = Students::findOne( ['id_org' => $row_org->id] );
                $row_org->system_status = ( $student ) ? 1 : 0;
                $row_org->save();

            }
            return true;
        } else
            return false;


    }

    public function actionKek(){
        $students = Students::find()->where(['date_start'=>'2020-04-01'])->all();
        foreach ($students as $student){
            $s = Students::find()->where(['name'=>$student->name,'MONTH(date_start)'=>03,'YEAR(date_start)'=>2020])->one();
            if ($s){
                $student->code = $s->code;
                $student->save(false);
            }
        }
    }

    public function actionStudents( $file, $nameId, $codeId, $dCreditId, $orgId, $numPP, $bankId, $dStart )
    {

        $csvP = Yii::getAlias( '@webroot' ) . "/toParse/$file.csv";

        $csv = fopen( $csvP, 'r' );
        if ( !$csvP )
            exit( "Файл не найден" );

        $row = fgetcsv( $csv, 1000, ';' ) ;
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
            КОД->$row[$codeId]
            Дата кредита->$row[$dCreditId]
            пп-> $num
            банк->$bank
            дата начала обуч->$row[$dStart]  \n";


        fclose( $csv );
        $csv = fopen( $csvP, 'r' );
        echo "Вы уверене? \n ";
        $key = readline();
        if ( !( $key === "yes" || $key === "y" || $key === "Y" ) ) {
            exit( 0 );
        }
        $countVip = 0;
        $countOtch = 0;
        $count = 0;
        $year = date('Y',strtotime($row[$dStart]));
        $month = date('m',strtotime($row[$dStart]))-1;

        while (( $row = fgetcsv( $csv, 1000, ';' ) ) != false) {

            $student = Students::find()->where(['name'=>$row[$nameId],'date_credit'=>$row[$dCreditId]])->one();
            if ($student and !$student->system_status)
                continue;
            if ($student and $student->isEnder ) {
                $countVip++;
                continue;
            }
            if ($student and !$student->education_status){
                $countOtch++;
                continue;
            }

            $student = new Students();
            $student->education_status = 1;
            $student->date_start = $row[ $dStart ];
            $student->name = $row[ $nameId ];
            $student->code = $row[ $codeId ];
            $student->date_credit = $row[ $dCreditId ];
            $student->id_org = $row[ $orgId ];
            $student->date_create = date( "Y-m-d" );
            $student->status = 1;
            $student->id_number_pp = $row[ $numPP ];
            $student->id_bank = $row[ $bankId ];

            if ( $student->save(false) ) {
                $count++;
                $org = Organizations::findOne( $student->id_org );
                if ( $org ) {
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
            }

        }
        echo "добавлено студентов $count \n отчислены в прошлом месяце $countOtch \n выпускники в прошлом месяце $countVip \n";


        fclose( $csv );
        echo "success!";
    }

    public function actionUpdateDates($sDate,$eDate){
        $sDate = explode('-',$sDate);
        $eDate = explode('-',$eDate);

        $students = Students::find()->where(['MONTH(date_start)'=>$sDate[1],'YEAR(date_start)'=>$sDate[0]])->all();

        foreach ($students as $student){
            $s = Students::find()->where(['date_credit'=>$student->date_credit,
                'MONTH(date_start)'=>$eDate[1],'YEAR(date_start)'=>$eDate[0]])->one();
            if ($s || !$student->education_status || $student->isEnder)
                continue;
            $s = clone $student;
            $s->date_start = "$eDate[0]-$eDate[1]-$eDate[1]";
            $s->isNewRecord = true;
            $s->id = null;
            $s->save(false);
        }
    }


}
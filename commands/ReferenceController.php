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

    public function actionDoubles($year,$month){
        $students = Students::find()->where(['YEAR(date_start)'=>$year,'MONTH(date_start)'=>$month,'id_number_pp'=>1,'id_bank'=>1,'system_status'=>1])->orderBy('id')->groupBy('name')->all();

        foreach ($students as $student){
            $s = Students::find()->where(['YEAR(date_start)'=>$year,'MONTH(date_start)'=>$month,'id_number_pp'=>1,'id_bank'=>1,'name'=>$student->name])->andWhere(['<>','id',$student->id])->all();
            if ($s){
                foreach ($s as $item){
                    $item->system_status=0;
                    $item->save(false);
                }
            }
        }
    }

    public function actionUpper(){
        $students = Students::find()->all();

        foreach ($students as $student){
            $b = $student->name;
            $student->name = mb_convert_case($student->name,MB_CASE_TITLE);
            if (!$student->education_status) $student->education_status =0;
            $student->save(false);
            echo "было $b стало $student->name \n";
        }





    }

    public function actionDate(){
        $st = Students::find()->all();
        foreach ($st as $item){
            $date = explode('-',$item->date_start);
            if (count($date)==3) {
                $item->date_start = "$date[0]-$date[1]-01";
                $item->save(false);
            }
        }
    }


    public function actionStudents( $file, $nameId, $dCreditId, $orgId, $numPP, $bankId, $dStart )
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

        if ($month < 1) {
            $month = 12;
            $year--;
        }

        while (( $row = fgetcsv( $csv, 32000, ';' ) ) != false) {

            $name = mb_convert_case($row[ $nameId ],MB_CASE_TITLE);

            $student2 = Students::find()
                ->where(['name'=>$name,'date_credit'=>$row[$dCreditId],
                    'YEAR(date_start)'=>date('Y',strtotime($row[$dStart])),
                    'MONTH(date_start)'=>date('m',strtotime($row[$dStart]))])->one();
            if ($student2) continue;
            $student2 = Students::find()->where(['name'=>$name,'date_credit'=>$row[$dCreditId],'YEAR(date_start)'=>$year,'MONTH(date_start)'=>$month])->one();
            $student = new Students();
            if ($student2 and $student2->isEnder ) {
                $student->education_status = 0;
                $student->isEnder = 1;
                $student->osnovanie = 0;
                $student->grace_period = 0;
                $student->date_ender = $student2->date_ender;
            }
            if ($student2 and !$student2->education_status and !$student2->isEnder){
                $student->education_status = 0;
                $student->osnovanie = $student2->osnovanie;
                $student->isEnder = 0;
                $student->grace_period = 0;
            }

            $student->code = $student2->code ?? 12345;

            $student->date_start = $row[ $dStart ];
            $student->name = $name;
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
            if ($student2 and !$student->system_status){
                $student->system_status = 0;
                $sh = StudentsHistory::findOne(['id_student'=>Students::find()->select('id')->where(['name'=>$student->name,'date_credit'=>$student->date_credit])->column()]);
                if ($sh){
                    $sh = new StudentsHistory();
                    $sh->id_student = $student->id;
                    $sh->id_change=1;
                    $sh->save();
                }
            }

        }
        echo "добавлено студентов $count \n отчислены в прошлом месяце $countOtch \n выпускники в прошлом месяце $countVip \n";


        fclose( $csv );
        echo "success!";
    }


}
<?php


namespace app\commands;


use app\models\app\Organizations;
use app\models\app\students\Students;

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

    public function actionDateUpdate($year,$month,$yearNew,$monthNew){
        $students = Students::find()->where(['system_status'=>1,'YEAR(date_start)'=>$year,'MONTH(date_start)'=>$month,'education_status'=>1])->all();
        foreach ($students as $student){
            $new_student = new Students();
            foreach (Students::getTableSchema()->getColumnNames() as $attribute){

                if ($attribute == 'id')
                    continue;
                $new_student->{$attribute} = $student->{$attribute};
                echo "$attribute = {$new_student->{$attribute}} \n";

            }
            $new_student->status=1;
            $new_student->date_start="{$yearNew}-{$monthNew}-01";
            $new_student->save(false);

        }
    }

    public function actionUpdate($from,$id_org,$npp, $onlyEnders = false, $updateDate = false){
        if (!$updateDate){
            $students = $onlyEnders ? Students::find()->where(['isEnder'=>true]) : Students::find() ;
            $students = $students->andWhere(['date_start'=>$from])->andWhere(['id_org'=>$id_org])->all();

            foreach ($students as $student){
                $s = Students::find()->where(['name'=>$student->name,'date_credit'=>$student->date_credit])->andWhere(['id_org'=>$id_org])->andWhere(['>','date_start',$from])->all();
                foreach ($s as $item){
                    $item->education_status = $student->education_status;
                    $item->osnovanie = $student->osnovanie;
                    $item->grace_period = $student->grace_period;
                    $item->isEnder = $student->isEnder;
                    $item->date_start_grace_period1 = $student->date_start_grace_period1;
                    $item->date_start_grace_period2 = $student->date_start_grace_period2;
                    $item->date_start_grace_period2 = $student->date_start_grace_period3;
                    $item->date_end_grace_period1 = $student->date_end_grace_period1;
                    $item->date_end_grace_period2 = $student->date_end_grace_period2;
                    $item->date_end_grace_period3 = $student->date_end_grace_period3;
                    $item->date_ender = $student->date_ender;
                    echo serialize($item);
                    $item->save(false);
                }
            }

        }
    }
    public function actionStudents2( $file, $nameId, $codeId, $dCreditId, $orgId, $numPP, $bankId, $dStart )
    {

        $csvP = Yii::getAlias( '@webroot' ) . "/toParse/$file.csv";

        $csv = fopen( $csvP, 'r' );
        if ( !$csvP )
            exit( "Файл не найден" );

        $row = fgetcsv( $csv, 1000, ';' ) ;

        echo "
            Организация->$row[$orgId]
            ФИО->$row[$nameId]
            КОД->$row[$codeId]
            Дата кредита->$row[$dCreditId]
            номер пп->$row[$numPP]
            нмоер банка->$row[$bankId]
            дата начала обуч->$row[$dStart]  \n";


        fclose( $csv );
        $csv = fopen( $csvP, 'r' );
        echo "Вы уверене? \n ";
        $key = readline();
        if ( !( $key === "yes" || $key === "y" || $key === "Y" ) ) {
            exit( 0 );
        }
        echo "fdsfsd";


        while (( $row = fgetcsv( $csv, 1000, ';' ) ) != false) {

            $student = Students::find()->where(['name'=>$row[$nameId],'date_credit'=>$row[$dCreditId],'YEAR(date_start)'=>2017])->one();
            if ($student) {
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
        fclose( $csv );
        echo "success!";
    }
    public function actionStudents( $file, $nameId, $codeId, $dCreditId, $orgId, $numPP, $bankId, $dStart )
    {

        $csvP = Yii::getAlias( '@webroot' ) . "/toParse/$file.csv";

        $csv = fopen( $csvP, 'r' );
        if ( !$csvP )
            exit( "Файл не найден" );

         $row = fgetcsv( $csv, 1000, ';' ) ;

            echo "
            Организация->$row[$orgId]
            ФИО->$row[$nameId]
            КОД->$row[$codeId]
            Дата кредита->$row[$dCreditId]
            номер пп->$row[$numPP]
            нмоер банка->$row[$bankId]
            дата начала обуч->$row[$dStart]  \n";


        fclose( $csv );
        $csv = fopen( $csvP, 'r' );
        echo "Вы уверене? \n ";
        $key = readline();
        if ( !( $key === "yes" || $key === "y" || $key === "Y" ) ) {
            exit( 0 );
        }
        echo "fdsfsd";


        while (( $row = fgetcsv( $csv, 1000, ';' ) ) != false) {

            $student = Students::find()->where(['name'=>$row[$nameId],'date_credit'=>$row[$dCreditId]])->one();
            if ($student) {
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
        fclose( $csv );
        echo "success!";
    }

    public function actionKek(){
        $students = Students::find()->where(['YEAR(date_start)'=>2017])->all();
        foreach ($students as $student)
            $student->delete();
        $students = Students::find()->where(['YEAR(date_start)'=>2019,'MONTH(date_start)'=>1,'id_number_pp'=>3,'id_bank'=>1,'system_status'=>1])->all();
        $keks = Students::getTableSchema()->getColumnNames();
        foreach ($students as $student) {
            $s = new Students();
            foreach ($keks as $atr){
                if ($atr == 'date_start' || $atr=='id')
                    continue;
                $s->$atr = $student->$atr;
            }
            $s->date_start = '2017-01-01';
            $s->save(false);
        }
    }



}
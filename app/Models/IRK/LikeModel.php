<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Helper\IRKHelper;

class LikeModel extends Model
{
    
	private $status = 'Failed';
    private $message = 'Data is cannot be process';
    private $data = [];

    public function __construct(Request $request, $slug)
    {
        // Call the parent constructor
        //parent::__construct();

        $helper = new IRKHelper($request);
        $this->helper = $helper;
        
        $segment = $helper->Segment($slug);
        $this->connection = $segment['connection'];
        $this->path = $segment['path'];
    }

    public function showDataLikeTotal($request)
    {
        $idticket = $request['idticket'];

        try
        {

            $data = $this->connection->select("select * from showlike(?)",[$idticket]);

            if($data) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data;
            } else{
                $this->status;
                $this->message;
                $this->data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function inputDataLike($request)
    {
        $param['list_sp'] = array([
            'conn'=>'POR_DUMMY',
            'payload'=>['nik' => $request['nik']],
            'sp_name'=>'SP_GetAccessLevel',
            'process_name'=>'GetAccessLevelResult'
        ]);

		$response = $this->helper->SPExecutor($param);
        
        if($response->status == 0){
            return [
                'status'  => $this->status,
                'data' => 'SPExecutor is cannot be process',
                'message' => $this->message
            ];
        }else{
            if(!empty($response->result->GetAccessLevelResult[0])){
                $level = $response->result->GetAccessLevelResult[0]->role;

                if(str_contains($level,'Admin') == false){
                    return [
                        'status'  => $this->status,
                        'data' => $level,
                        'message' => $this->message
                    ];
                }
            }else{
                $level = null;
            }
        }

        $nik = $request['nik'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $alias = str_contains($level,'Admin') && $request['tag'] == 'motivasi' ? $level : base64_encode(microtime().$request['nik']);
        $userlike = $request['userlike'];

        try
        {
            $data = $this->connection->insert("CALL inputlike(?,?,?,?,?)", [$nik,$idticket,$tag,$alias,$userlike]);

            if($data) {

                $target = $this->connection
                ->table('CeritaKita')
                ->select('CeritaKita.employee AS employee','CeritaKita.tag AS tag','CeritaKita.created_at AS created_at','CeritaKita.is_used AS is_used','ReportTicket.id_report AS id_report', 'Likes.like AS like')
                ->leftJoin('ReportTicket', 'ReportTicket.id_ticket','=','CeritaKita.id_ticket')
                ->leftJoin('Likes', 'Likes.id_ticket','=','CeritaKita.id_ticket')
                ->where('CeritaKita.id_ticket','=',$idticket)
                ->where('Likes.nik_karyawan','=',$nik)
                ->get()[0];

                $timestamp = $target->created_at;

                $carbonDate = Carbon::parse($timestamp);

                $dateOnly = $carbonDate->toDateString();

                $bundle = $this->connection->select("select * from showceritakitadetail(?,?,?,?,?,?,?)",
                        [$target->employee,0,$target->tag,$dateOnly,$dateOnly,empty($target->id_report) ? 'Tidak' : $target->id_report,$target->is_used]);

                $filterBundle = collect($bundle)->where('idticket', $idticket);

                for($index = 0; $index < count($filterBundle); $index++ ){
                    $filterBundle[$index]->comments = $this->connection->select("select * from showcomment(?)",[$filterBundle[$index]->idticket]);
                    for($comment = 0; $comment < count($filterBundle[$index]->comments); $comment++ ){
                        $filterBundle[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from showreportcomment(?)",[$filterBundle[$index]->comments[$comment]->id_comment]);
                        $filterBundle[$index]->report_comment = count($filterBundle[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
                    }
                    $filterBundle[$index]->likes = $this->connection->select("select * from showlike(?)",[$filterBundle[$index]->idticket]);
                    $filterBundle[$index]->report_ticketlist = $this->connection->select("select * from showreportticket(?)",[$filterBundle[$index]->idticket]);
                    $filterBundle[$index]->report_ticket = count($filterBundle[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
                }

                $toJson = json_encode($filterBundle[0]);

                $toBase64 = base64_encode($toJson);

                if($target->like === '1'){
                    $body['data'] = [
                        'nik'=>$target->employee,
                        'apps'=>'Web Admin IRK',
                        'nikLogin'=>$nik,
                        'shortMessage'=>'Like '.$target->tag,
                        'longMessage'=>'Random alias menyukai postingan anda',
                        'link'=>'portal/irk/transaksi/cerita-kita/rincian/redirect/'.$toBase64
                    ];
    
                    $response = $this->helper->NotificationPortal($body);
    
                    $this->status = 'Success';
                    $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan aktifkan izin notifikasi pada browser anda di halaman login terlebih dahulu';
                    $this->data = $data;
                }else{
                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = $data;
                }
            } else{
                $this->status;
                $this->message;
                $this->data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

}
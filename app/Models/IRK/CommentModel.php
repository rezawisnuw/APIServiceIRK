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

class CommentModel extends Model
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

    public function showDataCommentTotal($request)
    {
        $idticket = $request['idticket'];

        try
        {

            $data = $this->connection->select("select * from showcomment(?)",[$idticket]);

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

    public function inputDataComment($request)
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
        $comment = $request['comment'];
        $idticket = $request['idticket'];
        $alias = str_contains($level,'Admin') && $request['tag'] == 'motivasi' ? $level : base64_encode(microtime().$request['nik']);
        $tag = $request['tag'];

        try
        {
            
            $data = $this->connection->insert("CALL inputcomment(?,?,?,?,?)", [$nik,$comment,$idticket,$alias,$tag]);

            if($data) {

                $target = $this->connection
                ->table('CeritaKita')
                ->select('CeritaKita.employee AS employee','CeritaKita.tag AS tag','CeritaKita.created_at AS created_at','CeritaKita.is_used AS is_used','ReportTicket.id_report AS id_report')
                ->leftJoin('ReportTicket', 'ReportTicket.id_ticket','=','CeritaKita.id_ticket')
                ->where('CeritaKita.id_ticket','=',$idticket)
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

                $body['data'] = [
                    'nik'=>$target->employee,
                    'apps'=>'Web Admin IRK',
                    'nikLogin'=>$nik,
                    'shortMessage'=>'Comment '.$target->tag,
                    'longMessage'=>'Random alias mengomentari postingan anda',
                    'link'=>'portal/irk/transaksi/cerita-kita/rincian/redirect/'.$toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan aktifkan izin notifikasi pada browser anda di halaman login terlebih dahulu';
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

    public function inputDataReplyComment($request)
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
        $comment = $request['comment'];
        $idreply = $request['idreply'];
        $alias = str_contains($level,'Admin') && $request['tag'] == 'motivasi' ? $level : base64_encode(microtime().$request['nik']);
        $parentreply = $request['parentreply'];

        try
        {
            $data = $this->connection->insert("CALL inputreplycomment(?,?,?,?,?)", [$nik,$comment,$idreply,$alias,$parentreply]);

            if($data) {
                
                $target = $this->connection
                ->table('CeritaKita')
                ->select('CeritaKita.employee AS employee','CeritaKita.tag AS tag','CeritaKita.created_at AS created_at','CeritaKita.is_used AS is_used','ReportTicket.id_report AS id_report','Comment.nik_karyawan AS nik_karyawan')
                ->leftJoin('ReportTicket', 'ReportTicket.id_ticket','=','CeritaKita.id_ticket')
                ->leftJoin('Comment', 'Comment.id_ticket','=','Comment.id_ticket')
                ->where($parentreply == 0 ? 'Comment.id_comment' : 'Comment.id_reply_comment','=',$idreply)
                ->get()[0];

                $timestamp = $target->created_at;

                $carbonDate = Carbon::parse($timestamp);

                $dateOnly = $carbonDate->toDateString();

                $bundle = $this->connection->select("select * from showceritakitadetail(?,?,?,?,?,?,?)",
                        [$target->employee,0,$target->tag,$dateOnly,$dateOnly,empty($target->id_report) ? 'Tidak' : $target->id_report,$target->is_used]);

                for($index = 0; $index < count($bundle); $index++ ){
                    $bundle[$index]->comments = $this->connection->select("select * from showcomment(?)",[$bundle[$index]->idticket]);
                    for($comment = 0; $comment < count($bundle[$index]->comments); $comment++ ){
                        $bundle[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from showreportcomment(?)",[$bundle[$index]->comments[$comment]->id_comment]);
                        $bundle[$index]->report_comment = count($bundle[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
                    }
                    $bundle[$index]->likes = $this->connection->select("select * from showlike(?)",[$bundle[$index]->idticket]);
                    $bundle[$index]->report_ticketlist = $this->connection->select("select * from showreportticket(?)",[$bundle[$index]->idticket]);
                    $bundle[$index]->report_ticket = count($bundle[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
                }

                $toJson = json_encode($bundle[0]);

                $toBase64 = base64_encode($toJson);
               
                $body['data'] = [
                    'nik'=>$target->nik_karyawan,
                    'apps'=>'Web Admin IRK',
                    'nikLogin'=>$nik,
                    'shortMessage'=>'Reply Comment '.$target->tag,
                    'longMessage'=>'Random alias membalas komentar anda',
                    'link'=>'portal/irk/transaksi/cerita-kita/rincian/redirect/'.$toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan aktifkan izin notifikasi pada browser anda di halaman login terlebih dahulu';
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

    public function editDataComment($request)
    {

        $nik = $request['nik'];
        $idcomment = $request['idcomment'];
        $tag = $request['tag'];

        try
        {
            $data = $this->connection->insert("CALL editcomment(?,?,?)", [$nik,$idcomment,$tag]);

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

    public function editDataReplyComment($request)
    {

        $nik = $request['nik'];
        $idreplycomment = $request['idreplycomment'];

        try
        {
            $data = $this->connection->insert("CALL editreplycomment(?,?)", [$nik,$idreplycomment]);

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


}
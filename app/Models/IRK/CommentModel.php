<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
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
                ->select('employee','tag')
                ->where('id_ticket','=',$idticket)
                ->get()[0];

                $body['data'] = [
                    'nik'=>$target->employee,
                    'apps'=>'Web Admin IRK',
                    'nikLogin'=>$nik,
                    'shortMessage'=>'Postingan tag '.$target->tag.' mu dibalas',
                    'longMessage'=>$comment,
                    'link'=>'portal/irk/transaksi/cerita-kita/redirect/'
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
                if($parentreply == 0){
                    $target = $this->connection
                    ->table('Comment')
                    ->select('alias','nik_karyawan')
                    ->where('id_comment','=',$idreply)
                    ->get()[0];
                }else{
                    $target = $this->connection
                    ->table('ReplyComment')
                    ->select('alias','nik_karyawan')
                    ->where('id_reply_comment','=',$idreply)
                    ->get()[0];
                }
                
               
                $body['data'] = [
                    'nik'=>$target->nik_karyawan,
                    'apps'=>'Web Admin IRK',
                    'nikLogin'=>$nik,
                    'shortMessage'=>'Komentar alias '.substr($target->alias,3,8).' mu dibalas',
                    'longMessage'=>$comment,
                    'link'=>'portal/irk/transaksi/cerita-kita/redirect/'
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
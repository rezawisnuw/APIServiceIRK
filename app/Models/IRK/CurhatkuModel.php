<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Helper\IRKHelper;

class CurhatkuModel extends Model
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

    public function showDataCurhatku($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];
        
        try
        {
            $data = $this->connection->select("select * from showcurhatkulist(?,?)",[$userid,$page]);

            if($data) {
                $this->status = $page != 0 ? 'Processing' : 'Success';
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

        for($index = 0; $index < count($data); $index++ ){
            $data[$index]->comments = $this->connection->select("select * from showcomment(?)",[$data[$index]->idticket]);
            for($comment = 0; $comment < count($data[$index]->comments); $comment++ ){
                $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from showreportcomment(?)",[$data[$index]->comments[$comment]->id_comment]);
                $data[$index]->report_comment = count($data[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
            }
            $data[$index]->likes = $this->connection->select("select * from showlike(?)",[$data[$index]->idticket]);
            $data[$index]->report_ticketlist = $this->connection->select("select * from showreportticket(?)",[$data[$index]->idticket]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status'  => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataCurhatkuSingle($request)
    {
        $userid = $request['userid'];
        $idticket = $request['idticket'];

        try
        {
            $data = $this->connection->select("select * from showcurhatkudetail(?,?)",[$userid,$idticket]);

            if($data) {
                $this->status = 'Processing';
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

        for($index = 0; $index < count($data); $index++ ){
            $data[$index]->comments = $this->connection->select("select * from showcomment(?)",[$data[$index]->idticket]);
            for($comment = 0; $comment < count($data[$index]->comments); $comment++ ){
                $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from showreportcomment(?)",[$data[$index]->comments[$comment]->id_comment]);
                $data[$index]->report_comment = count($data[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
            }
            $data[$index]->likes = $this->connection->select("select * from showlike(?)",[$data[$index]->idticket]);
            $data[$index]->report_ticketlist = $this->connection->select("select * from showreportticket(?)",[$data[$index]->idticket]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status'  => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataCurhatkuTotal($request)
    {

        try
        {
            $data = $this->connection
            ->table('CeritaKita')
            ->select(DB::raw('count(*) as ttldatacurhatku'))  
            ->where('tag','=','curhatku')
            ->get();

            if($data) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0]->ttldatacurhatku;
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

    public function inputDataCurhatku($request)
    {

        $nik = $request->nik;
        $caption = $request->caption;
        $deskripsi = $request->deskripsi;
        $alias = base64_encode(microtime().$request->nik);
        $gambar = isset($request->gambar) ? $request->gambar : '';
        $tag = 'curhatku'; //$request->tag;

        try
        {
            $idimg = substr($alias,3,8);

            if(!empty($gambar)){
                $imgformat = array("jpeg", "jpg", "png");
                
                if ($gambar->getSize() > 1048576 || !in_array($gambar->extension(), $imgformat)){
                    return [
                        'status'  => 'File Error',
                        'data' => $this->data,
                        'message' => 'Format File dan Size tidak sesuai',
                        'code' => 200
                    ];
                }else{
                    
                    $imgextension = $gambar->extension();

                    $data = $this->connection->insert("CALL inputceritakita(?,?,?,?,?,?)", [$nik,$caption,$deskripsi,$alias,$idimg.'.'.$imgextension,$tag]);

                    if($data) {
                        $imgpath = $this->path.'/Ceritakita/Curhatku/'.$idimg.'.'.$imgextension;

                        $this->status = 'Success';
                        $this->message = 'Data has been process';
                        $this->data = $imgpath;
                    } else{
                        $this->status;
                        $this->message;
                        $this->data;
                    }
                }
            } else{
                $data = $this->connection->insert("CALL inputceritakita(?,?,?,?,?,?)", [$nik,$caption,$deskripsi,$alias,$idimg.'.',$tag]);
                
                if($data) {
                    $imgpath = $this->path.'/Ceritakita/Curhatku/'.$idimg.'.';

                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = $imgpath;
                }

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
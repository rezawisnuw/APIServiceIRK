<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
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
        $alias = str_contains($level,'Admin') ? $level : base64_encode(microtime().$request['nik']); //substr(base64_encode(microtime().$request['nik']),3,8);
        $userlike = $request['userlike'];

        try
        {
            $data = $this->connection->insert("CALL inputlike(?,?,?,?,?)", [$nik,$idticket,$tag,$alias,$userlike]);

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
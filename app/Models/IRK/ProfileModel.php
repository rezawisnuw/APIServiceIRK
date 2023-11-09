<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Helper\IRKHelper;

class ProfileModel extends Model
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

    public function showDataProfile($request)
    {
        
        $nik = $request['nik'];

        try
        {   
            $data = $this->connection
            ->table('UserStatus')
            ->where('nik','=',$nik)
            ->get();
            
            if($data) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0];
            }else{
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

    public function inputDataProfile($request)
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
            $level = $response->result->GetAccessLevelResult[0]->role;
        }

        $nik = $request['nik'];
        $nama = $request['nama'];
        $nohp = $request['nohp'];
        $alias = str_contains($level,'Admin') ? $level : base64_encode(microtime().$request['nik']); //substr(base64_encode(microtime().$request['nik']),3,8);
        $email = $request['email'];
        $kelamin = $request['kelamin'];
        $status = $request['status'];

        try
        {
            $data = $this->connection->insert("CALL inputuserstatus(?,?,?,?,?,?,?)", [$nik,$nama,$nohp,$alias,$email,$kelamin,$status]);

            if($data) {
                $this->status = isset($request['userid']) && !empty($request['userid']) ? 'Success' : 'Processing';
                $this->message = 'Data has been process';
                $this->data = isset($request['userid']) && !empty($request['userid']) ? $data : $alias;
            } else{
                $this->status;
                $this->message;
                $this->data;
            }

        }
        catch(\Exception $e){ 
            $this->status;
            $this->data;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

}
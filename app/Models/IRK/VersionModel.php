<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Helper\IRKHelper;

class VersionModel extends Model
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

    public function showDataAppVersion($request)
    {
        
        $ostype = $request['os_type'];

        try
        {   
            $data = $this->connection
            ->table('AppVersion')
            ->select('os_type','version_code','version_name')
            ->where('os_type','=',$ostype)
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

    public function inputDataAppVersion($request)
    {
        $ostype = $request['os_type'];
        $versioncode = $request['version_code'];
        $versionapp = $request['version_name'];

        try
        {
            $data = $this->connection->insert("CALL inputappversion(?,?,?)", [$ostype,$versioncode,$versionapp]);

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
<?php

namespace App\Helper;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;

class IRKHelper
{

    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();

        $this->request = $request;
    }
    public function Segment($slug){

        $setting = [];

        if($slug == 'dev'){
            $setting['authorize'] = 'Authorization-dev';
            $setting['config'] = config('app.URL_DEV');
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'));
            $setting['path'] = 'Dev';
        }else if($slug == 'stag'){
            $setting['authorize'] = 'Authorization-stag';
            $setting['config'] = config('app.URL_STAG');
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_STAG'));
            $setting['path'] = 'Stag';
        }else if($slug == 'live'){
            $setting['authorize'] = 'Authorization';
            $setting['config'] = config('app.URL_LIVE');
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_TEMP'));
            $setting['path'] = 'Live';
        }else{
            $response = collect([
                'status' => 'Failed',
                'data' => [],
                'message'  => 'Something is wrong with the path of URI segment'
            ]);
 
            $encode = json_encode($response);
            $encrypt = Crypt::encryptString($encode);
            $decrypt =  Crypt::decryptString($encrypt);
            $decode = json_decode($decrypt);
    
            return $decode;
        }
        
        return $setting;
    }

    public function BlobtoFile($blob){
        $arrayfile = [];
        $tmpFilePath = "example.txt";
        file_put_contents($tmpFilePath, $blob);
        $tmpFile = new File($tmpFilePath);
        $file = new UploadedFile(
            $tmpFile->getPathname(),    // Temporary file path on the server
            $tmpFile->getFilename(),    // Original file name
            $tmpFile->getMimeType(),    // MIME type
            $tmpFile->getSize(),        // File size
            0,                          // Manually setting error code to 0
            true                        // Setting the test flag to true
        );
        array_push($arrayfile, $file);

        return $arrayfile;
    }

    public function SPExecutor($param){

        $client = new Client(); 
        $response = $client->post(
            isset($param['list_sp']) && $param['list_sp'] != null ? 
            'http://'.$this->Segment($this->request->route('slug'))['config'].'/SPExecutor/SpExecutorRest.svc/executev2' : 
            'http://'.$this->Segment($this->request->route('slug'))['config'].'/SPExecutor/SpExecutorRest.svc/executev3' , 
            [
                'headers' => [
                    'Content-Type' => 'text/plain'
                ],
                'body' => json_encode([
                    'request' => $param
                ])
            ]
        );
        
        $body = $response->getBody();
        $result = json_decode($body);

        return $result;
    }

    public function NotificationPortal($param){

        $data = [
            'code'=>'1101',
            'parm'=>$param['data']
        ];

        $client = new Client(); 
        $response = $client->post(
            'http://'.$this->Segment($this->request->route('slug'))['config'].'/PortalRESTService/PortalService.svc/portalRest' , 
            [
                RequestOptions::JSON => 
				['req'=>$data]
            ],
            ['Content-Type' => 'application/json']
        );
        
        $body = $response->getBody();
        $result = json_decode($body);

        return $result;
    }

}
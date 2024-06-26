<?php

namespace App\Helper;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class IRKHelper
{
    private $request;
    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();

        $this->request = $request;
    }
    public function Segment($slug)
    {

        $setting = [];

        if ($slug == 'dev') {
            $setting['authorize'] = 'Authorization-dev';
            $setting['config'] = config('app.URL_DEV');
            $setting['credential'] = DB::connection(config('app.URL_PGSQLGCP_ESS'));// . $this->request->route('x') . '_DEV'));
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_V' . $this->request->route('x') . '_DEV'));
            $setting['path'] = 'Dev';
        } else if ($slug == 'stag') {
            $setting['authorize'] = 'Authorization-stag';
            $setting['config'] = config('app.URL_STAG');
            $setting['credential'] = DB::connection(config('app.URL_PGSQLGCP_ESS'));// . $this->request->route('x') . '_STAG'));
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_V' . $this->request->route('x') . '_STAG'));
            $setting['path'] = 'Stag';
        } else if ($slug == 'live') {
            $setting['authorize'] = 'Authorization';
            $setting['config'] = config('app.URL_LIVE');
            $setting['credential'] = DB::connection(config('app.URL_PGSQLGCP_ESS'));// . $this->request->route('x') . '_LIVE'));
            $setting['connection'] = DB::connection(config('app.URL_PGSQLGCP_IRK_V' . $this->request->route('x') . ''));
            $setting['path'] = 'Live';
        } else {
            $response = collect([
                'status' => 'Failed',
                'data' => [],
                'message' => 'Something is wrong with the path of URI segment'
            ]);

            $encode = json_encode($response);
            $encrypt = Crypt::encryptString($encode);
            $decrypt = Crypt::decryptString($encrypt);
            $decode = json_decode($decrypt);

            return $decode;
        }

        return $setting;
    }

    public function BlobtoFile($blob)
    {
        $arrayfile = [];
        $tmpFilePath = "example.txt";
        file_put_contents($tmpFilePath, $blob);
        $tmpFile = new File($tmpFilePath);
        $file = new UploadedFile(
            $tmpFile->getPathname(),    // Temporary file path on the server
            $tmpFile->getFilename(),    // Original file name
            $tmpFile->getMimeType(),    // MIME type
            $tmpFile->getSize(),        // File size
            0                          // Manually setting error code to 0
        );
        array_push($arrayfile, $file);

        return $arrayfile;
    }

    public function MultiBlobtoFile($blob)
    {
        $arrayfile = [];
        for ($i = 0; $i < count($blob); $i++) {
            foreach ($blob[$i] as $b) {
                $tmpFilePath = "example.txt";
                file_put_contents($tmpFilePath, base64_decode($b));
                $tmpFile = new File($tmpFilePath);
                $file = new UploadedFile(
                    $tmpFile->getPathname(),    // Temporary file path on the server
                    $tmpFile->getFilename(),    // Original file name
                    $tmpFile->getMimeType(),    // MIME type
                    $tmpFile->getSize(),        // File size
                    0                          // Manually setting error code to 0
                );
                array_push($arrayfile, $file);
            }
        }

        return $arrayfile;
    }

    public function SPExecutor($param)
    {

        $client = new Client();
        $response = $client->post(
            isset($param['list_sp']) && $param['list_sp'] != null ?
            'http://' . $this->Segment($this->request->route('slug'))['config'] . '/SPExecutor/SpExecutorRest.svc/executev2' :
            'http://' . $this->Segment($this->request->route('slug'))['config'] . '/SPExecutor/SpExecutorRest.svc/executev3',
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

    public function NotificationPortal($param)
    {

        $data = [
            'code' => '1101',
            'parm' => $param['data']
        ];

        $client = new Client();
        $response = $client->post(
            'http://' . $this->Segment($this->request->route('slug'))['config'] . '/PortalRESTService/PortalService.svc/portalRest',
            [
                RequestOptions::JSON =>
                    ['req' => $data]
            ]
        );

        $body = $response->getBody();
        $result = json_decode($body);

        return $result;
    }

    public function RecommenderSystem($userid, $page)
    {
        $result = '';
        $client = new Client();
        $postBody['nik'] = $userid;
        $postBody['page'] = $page;

        $response = $client->post(
            'https://ai.hrindomaret.com/get_recommendation',
            [
                RequestOptions::JSON => $postBody
            ]
        );
        $body = $response->getBody();
        $result = json_decode($body);

        return $result;
    }
}
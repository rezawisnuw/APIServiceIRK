<?php

namespace App\Http\Controllers\IRK;
use DB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Models\IRK\CeritakitaModel;
use App\Helper\IRKHelper;

use PHPUnit\Framework\Exception;

class CeritakitaController extends Controller
{
    private $status = 'Failed';
    private $data = [];
    private $message = 'Process is not found';

    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();
        
        $slug = $request->route('slug');
		$this->slug = $slug;

        $model = new CeritakitaModel($request, $slug);
        $this->model = $model;

        $helper = new IRKHelper($request);
		$this->helper = $helper;

    }

    public function get(Request $request)
    {
        $formbody = $request->data;
        $codekey = null;
        
        try{         
            
            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = $this->model->showDataCeritakita($formbody);
                    break;
                case 2:
                    $result = $this->model->showDataCeritakitaTotal($formbody);
                    break;
                case 3:
                    $result = $this->model->showDataCeritakitaSub($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $this->data,
                        'message' => $this->message
                    ]);
            }
				
        }
        catch(\Throwable $e){ 
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message'  => $e->getCode() == 0 ? 'Error Controller Laravel = '.$e->getMessage() : 'Error Model Laravel = '.$e->getMessage().' On Switch Case = '.$codekey
            ]);
        }

        return response()->json($result);
    }

    public function post(Request $request)
    {
        $codekey = null;

        $datadecode = json_decode($request->data);

        if(isset($request->file)){
            $filedecode = json_decode($request->file);
            $b64filedecode = base64_decode($filedecode);

            $arrayfile = $this->helper->BlobtoFile($b64filedecode);
            //return response()->json($arrayfile[0]->extension());
            isset($datadecode->gambar) && !empty($datadecode->gambar) ? $datadecode->gambar = $arrayfile[0] : $datadecode->gambar = '';
        }
        
        $formbody = $datadecode;
        
        try{         
            
            switch ($codekey = $formbody->code) {
                case 1:
                    $result = $this->model->inputDataCeritakita($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $this->data,
                        'message' => $this->message
                    ]);
            }
				
        }
        catch(\Throwable $e){ 
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message'  => $e->getCode() == 0 ? 'Error Controller Laravel = '.$e->getMessage() : 'Error Model Laravel = '.$e->getMessage().' Switch Case = '.$codekey
            ]);
        }

        return response()->json($result);
    }

    public function put(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
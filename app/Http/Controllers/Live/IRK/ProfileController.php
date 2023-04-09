<?php

namespace App\Http\Controllers\Live\IRK;
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
use App\Models\Live\IRK\ProfileModel;

use PHPUnit\Framework\Exception;

class ProfileController extends Controller
{
    private $status = 'Error';
    private $data = null;
    private $message = 'Process is not found';
    
    public function get(Request $request)
    {
        $formbody = $request->data;
        $codekey = null;
        
        try{         
            
            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = ProfileModel::showDataProfile($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $this->data,
                        'message' => $this->message
                    ]);
            }
				
        }
        catch(\Exception $e){ 
            $result = collect([
                'status' => $this->status,
                'data' => $this->data,
                'message'  => $e->getCode() == 0 ? 'Error Controller Laravel = '.$e->getMessage() : 'Error Model Laravel = '.$e->getMessage().' Switch Case = '.$codekey
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

            $arrayfile = [];
            $tmpFilePath = "example.txt";
            file_put_contents($tmpFilePath, $b64filedecode);
            $tmpFile = new File($tmpFilePath);
            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true // Mark it as test, since the file isn't from real HTTP POST.
            );
            array_push($arrayfile, $file);
            //return response()->json($arrayfile[0]->extension());
            isset($datadecode->photo) && !empty($datadecode->photo) ? $datadecode->photo = $arrayfile[0] : $datadecode->photo = '';
        }

        $formbody = $datadecode;
        
        try{         
            
            switch ($codekey = $formbody->code) {
                case 1:
                    $result = ProfileModel::inputDataProfile($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $this->data,
                        'message' => $this->message
                    ]);
            }
				
        }
        catch(\Exception $e){ 
            $result = collect([
                'status' => $this->status,
                'data' => $this->data,
                'message'  => $e->getCode() == 0 ? 'Error Controller Laravel = '.$e->getMessage() : 'Error Model Laravel = '.$e->getMessage().' Switch Case = '.$codekey
            ]);
        }

        return response()->json($result);
    }

    public function put(Request $request)
    {
        $codekey = null;

        $datadecode = json_decode($request->data);
        
        if(isset($request->file)){
            $filedecode = json_decode($request->file);
            $b64filedecode = base64_decode($filedecode);

            $arrayfile = [];
            $tmpFilePath = "example.txt";
            file_put_contents($tmpFilePath, $b64filedecode);
            $tmpFile = new File($tmpFilePath);
            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true // Mark it as test, since the file isn't from real HTTP POST.
            );
            array_push($arrayfile, $file);
            //return response()->json($arrayfile[0]->extension());
            isset($datadecode->photo) && !empty($datadecode->photo) ? $datadecode->photo = $arrayfile[0] : $datadecode->photo = '';
        }

        $formbody = $datadecode;
        
        try{         
            
            switch ($codekey = $formbody->code) {
                case 1:
                    $result = ProfileModel::editDataProfile($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $this->data,
                        'message' => $this->message
                    ]);
            }
				
        }
        catch(\Exception $e){ 
            $result = collect([
                'status' => $this->status,
                'data' => $this->data,
                'message'  => $e->getCode() == 0 ? 'Error Controller Laravel = '.$e->getMessage() : 'Error Model Laravel = '.$e->getMessage().' Switch Case = '.$codekey
            ]);
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {

    }
}
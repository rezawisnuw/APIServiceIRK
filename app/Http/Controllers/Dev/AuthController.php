<?php

namespace App\Http\Controllers\Dev;
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
use App\Models\Dev\AuthModel;

use PHPUnit\Framework\Exception;

class AuthController extends Controller
{
    private $status = 'Error';
    private $data = null;
    private $message = 'Process is not found';
    
    public function Authentication(Request $request)
    {
     
        $formbody = $request->data;
        $codekey = null;
        
        try{         
            
            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = AuthModel::Login($formbody);
                    break;
                case 2:
                    $result = AuthModel::Logout($formbody);
                    break;
                case 3:
                    $result = AuthModel::Authenticate($formbody);
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
}
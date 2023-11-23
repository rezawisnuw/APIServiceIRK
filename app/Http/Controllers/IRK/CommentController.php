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
use App\Models\IRK\CommentModel;
use App\Helper\IRKHelper;

use PHPUnit\Framework\Exception;

class CommentController extends Controller
{
    private $status = 'Failed';
    private $data = [];
    private $message = 'Process is not found';

    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();
        
        $slug = $request->route('slug');
		$this->slug = $slug.'-temp';

        $model = new CommentModel($request, $slug);
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
                    $result = $this->model->showDataCommentTotal($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $codekey,
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
        $formbody = $request->data;
        $codekey = null;
        
        try{         
            
            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = $this->model->inputDataComment($formbody);
                    break;
                case 2:
                    $result = $this->model->inputDataReplyComment($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $codekey,
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

    public function put(Request $request)
    {
        $formbody = $request->data;
        $codekey = null;
        
        try{         
            
            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = $this->model->editDataComment($formbody);
                    break;
                case 2:
                    $result = $this->model->editDataReplyComment($formbody);
                    break;
                default:
                    $result = collect([
                        'status'  => $this->status,
                        'data' => $codekey,
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

    public function delete(Request $request)
    {

    }
}
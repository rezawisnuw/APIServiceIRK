<?php

namespace App\Models\Live;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class AuthModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = 'Data is Empty';

    public static function Login($request)
    {
        $nik = $request['nik'];
        $password = $request['password'];

        try
        {
            $data = DB::connection(config('app.URL_SQLSRV93'))->select("EXEC [dbo].[LoginESSOnline]?,?", [$nik,$password]);

            $token = null;

            if($data) {
                $flag = $data[0]->flag;

                if($flag == 'sukses') {

                    $user = DB::connection(config('app.URL_SQLSRV93'))->select("EXEC [dbo].[ESSLoginSessionGet]?", [$nik]);
                    
                    if(count($user) != 0){
                        $credential = Credential::GetTokenAuth($user[0]->NikKaryawan);
                        
                        if(!empty($credential)){
                            $token = $credential['GetTokenForResult'];

                            static::$status = 'Success';
                            static::$message = 'Data has been process';
                            static::$data = $token;
                        }else{
                            $token = 'Cannot Generate Token';

                            static::$status;
                            static::$message;
                            static::$data = $token;
                        }
      
                    }else{
                        static::$status = 'Warning';
                        static::$message = 'You need to update your Head Department';
                        static::$data = null;
                    }

                }else{
                    static::$status = 'Unmatch';
                    static::$message = 'Username or Password is not correct, Please Try Again';               
                    static::$data = [
                        $nik,
                        $password
                    ];
                }
            }
            else{
                static::$status;
                static::$message;
                static::$data;
            }

        }
        catch(\Exception $e){ 
            static::$status;
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function Logout($request){
        try{
            $token = str_contains($request->cookie('Authorization'), 'Bearer') ? substr($request->cookie('Authorization'),6) : $request->cookie('Authorization');
            if($token != null){
                Cookie::queue(Cookie::forget('Authorization'));

                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $token;
            }else{
                static::$status;
                static::$message;
                static::$data = null;
            }
            
        }catch(Exception $e){
            static::$status;
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Other = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function Authenticate($request){
        try{
            $client = new Client(); 
            $response = $client->post(
                'http://'.config('app.URL_LIVE').'/RESTSecurity/RESTSecurity.svc/GetTokenDetail',
                [
                    RequestOptions::JSON => 
                    ['token' => str_contains($request->cookie('Authorization'), 'Bearer') ? substr($request->cookie('Authorization'),6) : $request->cookie('Authorization')]
                ],
                ['Content-Type' => 'application/json']
            );

            $responseBody = json_decode($response->getBody(), true);

            static::$status = 'Success';
            static::$message = 'Data has been process';
            static::$data = $responseBody;
            
        }catch(Exception $e){
            static::$status;
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Function WCF = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }
}
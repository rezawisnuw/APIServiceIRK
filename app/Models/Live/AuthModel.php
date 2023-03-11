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
    private static $message = 'Data gagal di proses';
    private static $data = 'Kosong';

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
                            static::$message = 'Data berhasil di proses';
                            static::$data = $token;
                        }else{
                            $token = 'Cannot Generate Token';

                            static::$status;
                            static::$message;
                            static::$data = $token;
                        }
      
                    }else{
                        static::$status = 'Warning';
                        static::$message = 'Anda perlu melakukan pengkinian data Atasan Anda';
                        static::$data = null;
                    }

                }else{
                    static::$status = 'Unmatch';
                    static::$message = 'Username atau Password yang Anda Masukkan Salah, Silahkan Coba Lagi';               
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
                static::$message = 'Data berhasil di proses';
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
                'http://'.config('app.URL_14_WCF').'/RESTSecurity/RESTSecurity.svc/GetTokenDetail',
                [
                    RequestOptions::JSON => 
                    ['token' => str_contains($request->cookie('Authorization'), 'Bearer') ? substr($request->cookie('Authorization'),6) : $request->cookie('Authorization')]
                ],
                ['Content-Type' => 'application/json']
            );

            $responseBody = json_decode($response->getBody(), true);

            static::$status = 'Success';
            static::$message = 'Data berhasil di proses';
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
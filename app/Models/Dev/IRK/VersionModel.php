<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class VersionModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = null;

    public static function showDataAppVersion($request)
    {
        
        $ostype = $request['ostype'];

        try
        {   
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
            ->table('AppVersion')
            ->where('OS_Type','=',$ostype)
            ->get();
            
            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data;
            }else{
                static::$status;
                static::$message;
                static::$data;
            }
            

        }
        catch(\Exception $e){ 
            static::$status;
            static::$data;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }
                    
        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function inputDataAppVersion($request)
    {
        $ostype = $request['ostype'];
        $versioncode = $request['versioncode'];
        $versionapp = $request['versionapp'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->insert("CALL inputappversion(?,?,?)", [$ostype,$versioncode,$versionapp]);

            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data;
            } else{
                static::$status;
                static::$message;
                static::$data;
            }

        }
        catch(\Exception $e){ 
            static::$status;
            static::$data;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

}
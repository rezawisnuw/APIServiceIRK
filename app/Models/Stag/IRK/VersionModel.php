<?php

namespace App\Models\Stag\IRK;

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
        
        $ostype = $request['os_type'];

        try
        {   
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_STAG'))
            ->table('AppVersion')
            ->select('OS_Type as os_type','Version_Code as version_code', 'Version_App as version_name')
            ->where('OS_Type','=',$ostype)
            ->get();
            
            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data[0];
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
        $ostype = $request['os_type'];
        $versioncode = $request['version_code'];
        $versionapp = $request['version_name'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_STAG'))->insert("CALL inputappversion(?,?,?)", [$ostype,$versioncode,$versionapp]);

            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data[0];
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
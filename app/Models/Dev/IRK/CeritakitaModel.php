<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CeritakitaModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = null;

    public static function showDataCeritakita($request)
    {
        
        $userid = $request['userid'];
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;

        try
        {   
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showceritakita(?,?)",[$userid,$page]);
            
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

        for($index = 0; $index < count($data); $index++ ){
            $data[$index]->comment = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showcomment(?,?)",[$data[$index]->idticket,$data[$index]->key]);
            $data[$index]->like = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showlike(?,?)",[$data[$index]->idticket,$data[$index]->key]);
        }
                    
        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

}
<?php

namespace App\Models\Live\IRK;

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
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showceritakitalist(?,?)",[$userid,$page]);

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

        for($index = 0; $index < count($data); $index++ ){
            $data[$index]->alias = substr($data[$index]->alias,3,8);
            $data[$index]->comments = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcomment(?)",[$data[$index]->idticket]);
            $data[$index]->likes = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showlike(?)",[$data[$index]->idticket]);
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataCeritakitaTotal($request)
    {

        try
        {
            // $data1 = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            // ->table('TicketCurhatku')
            // ->select(DB::raw('count(*) as ttldatacurhatku'))  
            // ->get();

            // $data2 = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            // ->table('TicketMotivasi')
            // ->select(DB::raw('count(*) as ttldatamotivasi'))  
            // ->get();

            //$data = $data1[0]->ttldatacurhatku + $data2[0]->ttldatamotivasi;

            $data3 = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('CeritaKita')
            ->select(DB::raw('count(*) as ttldataceritakita'))  
            ->get();

            $data = $data3[0]->ttldataceritakita;

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
<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ReportModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = null;

    public static function showDataReportTicket($request)
    {
        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
            ->table('ReportDetails')
            ->orderBy('Created_at','DESC')
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

    public static function showDataReportComment($request)
    {
        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
            ->table('ReportCommentDetails')
            ->orderBy('Created_at','DESC')
            ->get();

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

    public static function inputDataReportTicket($request)
    {
        $nik = $request['nik'];
        $report = $request['report'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->insert("CALL inputreportticket(?,?,?,?)", [$nik,$report,$idticket,$tag]);

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

    public static function inputDataReportComment($request)
    {
        $nik = $request['nik'];
        $report = $request['report'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->insert("CALL inputreportcomment(?,?,?,?)", [$nik,$report,$idticket,$tag]);

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
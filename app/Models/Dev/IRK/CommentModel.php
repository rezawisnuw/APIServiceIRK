<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CommentModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = 'Data is Empty';

    public static function showDataCommentTotal($request)
    {
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try
        {
            // $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            // ->table('Comment')
            // ->leftJoin('CommentDetails','Comment.Id_Comment','=','CommentDetails.Id_Comment')
            // ->where('Comment.Tag','=',$tag)
            // ->where('Comment.Id_Ticket','=',$idticket)
            // ->orderBy('CommentDetails.Created_at','DESC')
            // ->get();

            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcomment(?,?)",[$idticket,$tag]);

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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    // public static function showDataCommentCurhatku($request)
    // {
    //     $idticket = $request['idticket'];

    //     try
    //     {
    //         $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
    //         ->table('Comment')
    //         ->select('CommentDetails.Nik_Karyawan','CommentDetails.Comment','CommentDetails.Alias','CommentDetails.Created_at')//,'UserProfile.Alias')
    //         ->leftJoin('CommentDetails','Comment.Id_Comment','=','CommentDetails.Id_Comment')
    //         //->leftJoin('UserProfile','CommentDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
    //         ->where('Comment.Tag','=','curhatku')
    //         ->where('Comment.Id_Ticket','=', $idticket)
    //         ->orderBy('CommentDetails.Created_at','DESC')
    //         ->get();


    //         if($data) {
    //             static::$status = 'Success';
    //             static::$message = 'Data has been process';
    //             static::$data = $data;
    //         } else{
    //             static::$status;
    //             static::$message;
    //             static::$data;
    //         }

    //     }
    //     catch(\Exception $e){ 
    //         static::$status;
    //         static::$data = null;
    //         static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
    //     }

    //     return [
    //         'status'  => static::$status,
    //         'data' => static::$data,
    //         'message' => static::$message
    //     ];
    // }

    // public static function showDataCommentMotivasi($request)
    // {
    //     $idticket = $request['idticket'];

    //     try
    //     {
    //         $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
    //         ->table('Comment')
    //         ->select('CommentDetails.Nik_Karyawan','CommentDetails.Comment','CommentDetails.Alias','CommentDetails.Created_at')//,'UserProfile.Alias')
    //         ->leftJoin('CommentDetails','Comment.Id_Comment','=','CommentDetails.Id_Comment')
    //         //->leftJoin('UserProfile','CommentDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
    //         ->where('Comment.Tag','=','motivasi')
    //         ->where('Comment.Id_Ticket','=', $idticket)
    //         ->orderBy('CommentDetails.Created_at','DESC')
    //         ->get();


    //         if($data) {
    //             static::$status = 'Success';
    //             static::$message = 'Data has been process';
    //             static::$data = $data;
    //         } else{
    //             static::$status;
    //             static::$message;
    //             static::$data;
    //         }

    //     }
    //     catch(\Exception $e){ 
    //         static::$status;
    //         static::$data = null;
    //         static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
    //     }

    //     return [
    //         'status'  => static::$status,
    //         'data' => static::$data,
    //         'message' => static::$message
    //     ];
    // }

    public static function inputDataComment($request)
    {
        $nik = $request['nik'];
        $comment = $request['comment'];
        $idticket = $request['idticket'];
        $alias = base64_encode(microtime().$request['nik']);//substr(base64_encode(microtime().$request['nik']),3,8);
        $tag = $request['tag'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcomment(?,?,?,?,?)", [$nik,$comment,$idticket,$alias,$tag]);

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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

}
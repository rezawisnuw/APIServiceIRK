<?php

namespace App\Models\Live\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class LikeModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = 'Data is Empty';

    public static function showDataLikeTotal($request)
    {
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try
        {
            // $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            // ->table('Likes')
            // ->leftJoin('LikesDetails','Likes.Id_Likes','=','LikesDetails.Id_Likes')
            // ->where('Likes.Tag','=',$tag)
            // ->where('Likes.Id_Ticket','=', $idticket)
            // ->orderBy('LikesDetails.Created_at','DESC')
            // ->get();

            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showlike(?,?)",[$idticket,$tag]);

            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data;
            } else{
                static::$status;
                static::$message;
                static::$data = $data;
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

    // public static function showDataLikeCurhatku($request)
    // {
    //     $idticket = $request['idticket'];

    //     try
    //     {
    //         $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
    //         ->table('Likes')
    //         ->select('LikesDetails.Nik_Karyawan','LikesDetails.Like','LikeDetails.Alias','LikesDetails.Created_at')//,'UserProfile.Alias')
    //         ->leftJoin('LikesDetails','Likes.Id_Likes','=','LikesDetails.Id_Likes')
    //         //->leftJoin('UserProfile','LikesDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
    //         ->where('Likes.Tag','=','curhatku')
    //         ->where('Likes.Id_Ticket','=', $idticket)
    //         ->orderBy('LikesDetails.Created_at','DESC')
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

    // public static function showDataLikeMotivasi($request)
    // {
    //     $idticket = $request['idticket'];

    //     try
    //     {
    //         $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
    //         ->table('Likes')
    //         ->select('LikesDetails.Nik_Karyawan','LikesDetails.Like','LikeDetails.Alias','LikesDetails.Created_at')//,'UserProfile.Alias')
    //         ->leftJoin('LikesDetails','Likes.Id_Likes','=','LikesDetails.Id_Likes')
    //         //->leftJoin('UserProfile','LikesDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
    //         ->where('Likes.Tag','=','motivasi')
    //         ->where('Likes.Id_Ticket','=', $idticket)
    //         ->orderBy('LikesDetails.Created_at','DESC')
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

    public static function inputDataLike($request)
    {
        $nik = $request['nik'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $alias = base64_encode(microtime().$request['nik']);//substr(base64_encode(microtime().$request['nik']),3,8);
        $userlike = $request['userlike'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputlike(?,?,?,?,?)", [$nik,$idticket,$tag,$alias,$userlike]);

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
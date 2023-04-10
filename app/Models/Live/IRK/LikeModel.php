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
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Likes')
            ->where('Likes.Tag','=',$tag)
            ->where('Likes.Id_Ticket','=', $idticket)
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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataLikeCurhatku($request)
    {
        $idticket = $request['idticket'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Likes')
            ->select('LikesDetails.Nik_Karyawan','LikesDetails.Like','LikesDetails.Created_at','UserProfile.Alias')
            ->leftJoin('LikesDetails','Likes.Id_Likes','=','LikesDetails.Id_Likes')
            ->leftJoin('UserProfile','LikesDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
            ->where('Likes.Tag','=','curhatku')
            ->where('Likes.Id_Ticket','=', $idticket)
            ->orderBy('LikesDetails.Created_at','DESC')
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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataLikeMotivasi($request)
    {
        $idticket = $request['idticket'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Likes')
            ->select('LikesDetails.Nik_Karyawan','LikesDetails.Like','LikesDetails.Created_at','UserProfile.Alias')
            ->leftJoin('LikesDetails','Likes.Id_Likes','=','LikesDetails.Id_Likes')
            ->leftJoin('UserProfile','LikesDetails.Nik_Karyawan', '=' ,'UserProfile.Nik_Karyawan')
            ->where('Likes.Tag','=','motivasi')
            ->where('Likes.Id_Ticket','=', $idticket)
            ->orderBy('LikesDetails.Created_at','DESC')
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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function inputDataLike($request)
    {
        $nik = $request['nik'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $userlike = $request['userlike'];

        try
        {
            //$data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputlike(?,?,?)", [$nik,$idticket,$tag]);
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select inputlike(?,?,?,?)", [$nik,$idticket,$tag,$userlike]);

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
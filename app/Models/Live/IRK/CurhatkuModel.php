<?php

namespace App\Models\Live\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CurhatkuModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = null;

    public static function showDataCurhatku($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('TicketCurhatku')
            ->select('Id_Ticket as idticket', 'Nik_Karyawan as employee', 'Caption as header', 'Deskripsi as text', 'Gambar as picture', 'Tag as key', 
                DB::raw('case when "Alias" is not null then substring("Alias" from 3 for 8) else "Alias" end as alias'),'Created_at as created',
                DB::raw('(select count(*) from "Comment" where "Tag" = \'curhatku\' and "Id_Ticket" = "TicketCurhatku"."Id_Ticket") as ttlcomment'),
                DB::raw('(select count(*) from "Likes" left join "LikesDetails" on "Likes"."Id_Likes" = "LikesDetails"."Id_Likes" where "Likes"."Tag" = \'curhatku\' and "Likes"."Id_Ticket" = "TicketCurhatku"."Id_Ticket" and "LikesDetails"."Like" = \'1\') as ttllike'),
                DB::raw('(select case when "Nik_Karyawan" = \'.$userid.\' then \'1\' else \'0\' end as liked from "Likes" left join "LikesDetails" on "Likes"."Id_Likes" = "LikesDetails"."Id_Likes" where "Likes"."Tag" = \'curhatku\' and "Likes"."Id_Ticket" = "TicketCurhatku"."Id_Ticket" order by "LikesDetails"."Created_by" = \'.$userid.\' desc limit 1) as likeby')
            )
            ->orderBy('created','DESC')
            ->limit(10)
            ->offset($page * 10)
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

        for($index = 0; $index < count($data); $index++ ){
            $data[$index]->comment = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcomment(?,?)",[$data[$index]->idticket,$data[$index]->key]);
            $data[$index]->like = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showlike(?,?)",[$data[$index]->idticket,$data[$index]->key]);
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataCurhatkuSingle($request)
    {
        $userid = $request['userid'];
        $idticket = $request['idticket'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcurhatku(?,?)",[$userid,$idticket]);

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
            $data[$index]->comment = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcomment(?,?)",[$data[$index]->idticket,$data[$index]->key]);
            $data[$index]->like = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showlike(?,?)",[$data[$index]->idticket,$data[$index]->key]);
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataCurhatkuTotal($request)
    {

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('TicketCurhatku')
            ->select(DB::raw('count(*) as ttldatacurhatku'))  
            ->get();

            if($data) {
                static::$status = 'Success';
                static::$message = 'Data has been process';
                static::$data = $data[0]->ttldatacurhatku;
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

    public static function inputDataCurhatku($request)
    {
        $nik = $request->nik;
        $caption = $request->caption;
        $deskripsi = $request->deskripsi;
        $alias = base64_encode(microtime().$request->nik);//substr(base64_encode(microtime().$request->nik),3,8);
        $gambar = isset($request->gambar) ? $request->gambar : '';

        try
        {
            if(!empty($gambar)){
                $imgformat = array("jpeg", "jpg", "png");
        
                if ($gambar->getSize() > 1048576 || !in_array($gambar->extension(), $imgformat)){
                    return [
                        'status'  => 'File Error',
                        'data' => static::$data,
                        'message' => 'Format File dan Size tidak sesuai',
                        'code' => 200
                    ];
                }else{
                    $imgextension = $gambar->extension();

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?,?)", [$nik,$caption,$deskripsi,$alias,$imgextension]);

                    if($data) {
                        $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                    ->table('TicketCurhatku')
                                    ->selectRaw('max(cast(left("Id_Ticket",length("Id_Ticket")-2) as integer))||'.'-C'.' as next_id')
                                    ->value('next_id');

                        $imgpath = 'Live/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.'.$imgextension;

                        static::$status = 'Success';
                        static::$message = 'Data has been process';
                        static::$data = $imgpath;
                    } else{
                        static::$status;
                        static::$message;
                        static::$data;
                    }
                }
            } else{
                $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?)", [$nik,$caption,$deskripsi,'']);
                
                if($data) {
                    $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                ->table('TicketCurhatku')
                                ->selectRaw('MAX("Id_Ticket") as next_id')
                                ->value('next_id');

                    $imgpath = 'Live/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.';

                    static::$status = 'Success';
                    static::$message = 'Data has been process';
                    static::$data = $imgpath;
                }

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
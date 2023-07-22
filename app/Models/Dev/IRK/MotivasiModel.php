<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class MotivasiModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = null;

    public static function showDataMotivasi($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];
        
        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
            ->table('TicketMotivasi')
            ->select('id_ticket as idticket', 'id_user as employee', 'judul_motivasi as header', 'motivasi as text', 'photo as picture', 'tag as key', 
                DB::raw('case when "alias" is not null then substring("alias" from 3 for 8) else "alias" end as alias'), 'addtime as created',
                DB::raw('(select count(*) from "Comment" where "Tag" = \'motivasi\' and "Id_Ticket" = "TicketMotivasi"."id_ticket") as ttlcomment'),
                DB::raw('(select count(*) from "Likes" left join "LikesDetails" on "Likes"."Id_Likes" = "LikesDetails"."Id_Likes" where "Likes"."Tag" = \'motivasi\' and "Likes"."Id_Ticket" = "TicketMotivasi"."id_ticket" and "LikesDetails"."Like" = \'1\') as ttllike'),
                DB::raw('(select case when "Nik_Karyawan" = \'.$userid.\' then \'1\' else \'0\' end as liked from "Likes" left join "LikesDetails" on "Likes"."Id_Likes" = "LikesDetails"."Id_Likes" where "Likes"."Tag" = \'motivasi\' and "Likes"."Id_Ticket" = "TicketMotivasi"."id_ticket" order by "LikesDetails"."Created_by" = \'.$userid.\' desc limit 1) as likeby')
            )
            ->orderBy('addtime','DESC')
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
            $data[$index]->comment = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showcomment(?,?)",[$data[$index]->idticket,$data[$index]->key]);
            $data[$index]->like = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showlike(?,?)",[$data[$index]->idticket,$data[$index]->key]);
        }

        return [
            'status'  => static::$status,
            'data' => static::$data,
            'message' => static::$message
        ];
    }

    public static function showDataMotivasiSingle($request)
    {
        $userid = $request['userid'];
        $idticket = $request['idticket'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->select("select * from showmotivasi(?,?)",[$userid,$idticket]);

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

    public static function inputDataMotivasi($request)
    {

        $nik = $request->nik; 
        $judul = $request->judul;
        $motivasi = $request->motivasi;
        $alias = base64_encode(microtime().$request->nik);//substr(base64_encode(microtime().$request->nik),3,8);
        $photo = isset($request->photo) ? $request->photo : '';

        try
        {
            if(!empty($photo)){
                $imgformat = array("jpeg", "jpg", "png");
        
                if ($photo->getSize() > 1048576 || !in_array($photo->extension(), $imgformat)){
                    return [
                        'status'  => 'File Error',
                        'data' => static::$data,
                        'message' => 'Format File dan Size tidak sesuai',
                        'code' => 200
                    ];
                }else{
                    $imgextension = $photo->extension();

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->insert("CALL inputmotivasi(?,?,?,?,?)", [$nik,$judul,$motivasi,$alias,$imgextension]);
                
                    if($data) {
                        $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
                                    ->table('TicketMotivasi')
                                    ->selectRaw('MAX("id_ticket") as next_id')
                                    ->value('next_id');

                        // $client = new Client();
                        // $response = $client->post(
                        //         'https://cloud.hrindomaret.com/api/irk/upload',
                        //         [
                        //             RequestOptions::JSON => 
                        //             [
                        //              'file'=> $photo,
                        //              'file_name' => 'Dev/'.$nik.'_'.$nextId.'.'.$imgextension
                        //             ]
                        //         ]
                        //     );

                        // $body = $response->getBody();
                        
                        // $temp = json_decode($body);

                        $imgpath = 'Dev/Ceritakita/Motivasi/'.$nik.'_'.$nextId.'.'.$imgextension;

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
                $data = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))->insert("CALL inputmotivasi(?,?,?,?,?)", [$nik,$judul,$motivasi,$alias,'']);
                
                if($data) {
                    $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK_DEV'))
                                ->table('TicketMotivasi')
                                ->selectRaw('MAX("id_ticket") as next_id')
                                ->value('next_id');

                    // $client = new Client();
                    // $response = $client->post(
                    //         'https://cloud.hrindomaret.com/api/irk/upload',
                    //         [
                    //             RequestOptions::JSON => 
                    //             [
                    //              'file'=> $photo,
                    //              'file_name' => 'Dev/'.$nik.'_'.$nextId.'.'.$imgextension
                    //             ]
                    //         ]
                    //     );

                    // $body = $response->getBody();
                    
                    // $temp = json_decode($body);

                    $imgpath = 'Dev/Ceritakita/Motivasi/'.$nik.'_'.$nextId.'.';

                    static::$status = 'Success';
                    static::$message = 'Data has been process';
                    static::$data = $imgpath;
                } else{
                    static::$status;
                    static::$message;
                    static::$data;
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
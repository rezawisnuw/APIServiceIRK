<?php

namespace App\Models\Stag\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CurhatkuModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = 'Data is Empty';

    public static function showDataCurhatku($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        
        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Ticket_Curhatku')
            ->select('Id_Ticket as idticket', 'Nik_Karyawan as employee', 'Caption as header', 'Deskripsi as text', 'Gambar as picture', 'Tag as key', 
                DB::raw('case when "Alias" is not null then substring("Alias" from 3 for 8) else "Alias" end as alias'),'Created_at as created')
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
            static::$data = null;
            static::$message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
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
            static::$data = null;
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
        $gambar = $request->gambar;

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

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?)", [$nik,$caption,$deskripsi,$imgextension]);

                    if($data) {
                        $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                    ->table('Ticket_Curhatku')
                                    ->selectRaw('CAST(MAX("Id_Ticket") as integer) as next_id')
                                    ->value('next_id');

                        $imgpath = 'Stag/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.'.$imgextension;

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
                                ->table('Ticket_Curhatku')
                                ->selectRaw('CAST(MAX("Id_Ticket") as integer) as next_id')
                                ->value('next_id');

                    $imgpath = 'Stag/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.';

                    static::$status = 'Success';
                    static::$message = 'Data has been process';
                    static::$data = $imgpath;
                }

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
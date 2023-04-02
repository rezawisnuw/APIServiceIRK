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
        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Ticket_Curhatku')
            ->orderBy('Id_Ticket','DESC')
            ->limit(35)
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

    public static function inputDataCurhatku($request)
    {
        $nik = $request->nik;
        $alias = $request->alias;
        $deskripsi = $request->deskripsi;
        $gambar = $request->gambar;

        try
        {
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

                $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?)", [$nik,$alias,$deskripsi,$imgextension]);

                if($data) {
                    $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                ->table('Ticket_Curhatku')
                                ->selectRaw('CAST(MAX("Id_Ticket") as integer) + 1 as next_id')
                                ->value('next_id');

                    $imgpath = 'Dev/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.'.$imgextension;

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
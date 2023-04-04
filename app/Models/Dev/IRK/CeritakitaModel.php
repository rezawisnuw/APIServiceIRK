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
    private static $data = 'Data is Empty';

    public static function showDataCeritakita($request)
    {
        try
        {   
            $second = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Ticket_Motivasi')
            ->select('id_ticket as IdTicket', 'id_user as Employee', 'judul_motivasi as Header', 'motivasi as Text', 'photo as Picture', 'tag as Key', 'addtime as Created')
            ->limit(35);

            $first = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('Ticket_Curhatku')
            ->select('Id_Ticket as IdTicket', 'Nik_Karyawan as Employee', 'Alias as Header', 'Deskripsi as Text', 'Gambar as Picture', 'Tag as Key', 'Created_at as Created')
            ->limit(35);

            $data = $first->union($second)->orderBy('Created','DESC')->get();

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
<?php

namespace App\Models\Dev\IRK;

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
            ->orderBy('Created_at','DESC')
            ->limit(35)
            ->get();

            if($data) {
                
                // $client = new Client();
                // $filesplit =  explode("/",$data[0]->Gambar);
                // $response = $client->get(
                //         'https://cloud.hrindomaret.com/api/irk/download?file_name='.$filesplit[0].'%2f'.$filesplit[1],
                //         [
                //             'query' => [
                //                 'file_name' => $filesplit[0].'%2f'.$filesplit[1]
                //             ]
                //         ]
                //     );

                // $body = $response->getBody();
                
                // $temp = json_decode($body);

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
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->select("select * from showcurhatku(?,?,?)",[$userid,$idticket,$page]);

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

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?)", [$nik,$alias,$deskripsi,$imgextension]);
                
                    if($data) {
                        $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                    ->table('Ticket_Curhatku')
                                    ->selectRaw('CAST(MAX("Id_Ticket") as integer) as next_id')
                                    ->value('next_id');

                        // $client = new Client();
                        // $response = $client->post(
                        //         'https://cloud.hrindomaret.com/api/irk/upload',
                        //         [
                        //             RequestOptions::JSON => 
                        //             [
                        //              'file'=> $gambar,
                        //              'file_name' => 'Dev/'.$nik.'_'.$nextId.'.'.$imgextension
                        //             ]
                        //         ]
                        //     );

                        // $body = $response->getBody();
                        
                        // $temp = json_decode($body);

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
            } else{
                $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputcurhatku(?,?,?,?)", [$nik,$alias,$deskripsi,'']);
                
                if($data) {
                    $nextId = DB::connection(config('app.URL_PGSQLGCP_IRK'))
                                ->table('Ticket_Curhatku')
                                ->selectRaw('CAST(MAX("Id_Ticket") as integer) as next_id')
                                ->value('next_id');

                    // $client = new Client();
                    // $response = $client->post(
                    //         'https://cloud.hrindomaret.com/api/irk/upload',
                    //         [
                    //             RequestOptions::JSON => 
                    //             [
                    //              'file'=> $gambar,
                    //              'file_name' => 'Dev/'.$nik.'_'.$nextId.'.'.$imgextension
                    //             ]
                    //         ]
                    //     );

                    // $body = $response->getBody();
                    
                    // $temp = json_decode($body);

                    $imgpath = 'Dev/Ceritakita/Curhatku/'.$nik.'_'.$nextId.'.';

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
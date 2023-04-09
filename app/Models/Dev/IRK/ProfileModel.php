<?php

namespace App\Models\Dev\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ProfileModel extends Model
{
    
	private static $status = 'Failed';
    private static $message = 'Data is cannot be process';
    private static $data = 'Data is Empty';

    public static function showDataProfile($request)
    {
        $nik = $request['nik'];

        try
        {
            $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))
            ->table('UserProfile')
            ->where('Nik_Karyawan','=', $nik)
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

    public static function inputDataProfile($request)
    {
        $nik = $request->nik;
        $nama = $request->nama;
        $nohp = $request->nohp;
        $alias = $request->alias;
        $kelamin = $request->kelamin;
        $email = $request->email;
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

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputProfile(?,?,?,?,?,?,?)", [$nik,$nama,$nohp,$alias,$kelamin,$email,$imgextension]);

                    if($data) {

                        $imgpath = 'Dev/Ceritakita/Profile/'.$nik.'_'.$nama.'.'.$imgextension;

                        static::$status = 'Success';
                        static::$message = 'Data has been process';
                        static::$data = $imgpath;
                    } else{
                        static::$status;
                        static::$message;
                        static::$data;
                    }
                }
            }else{
                $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL inputProfile(?,?,?,?,?,?,?)", [$nik,$nama,$nohp,$alias,$kelamin,$email,'']);
                
                if($data) {
                    $imgpath = 'Dev/Ceritakita/Profile/'.$nik.'_'.$nama.'.';

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

    public static function editDataProfile($request)
    {
        $nik = $request->nik;
        $nama = $request->nama;
        $nohp = $request->nohp;
        $alias = $request->alias;
        $kelamin = $request->kelamin;
        $email = $request->email;
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

                    $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL editProfile(?,?,?,?,?,?,?)", [$nik,$nama,$nohp,$alias,$kelamin,$email,$photo]);

                    if($data) {

                        $imgpath = 'Dev/Ceritakita/Profile/'.$nik.'_'.$nama.'.'.$imgextension;

                        static::$status = 'Success';
                        static::$message = 'Data has been process';
                        static::$data = $imgpath;
                    } else{
                        static::$status;
                        static::$message;
                        static::$data;
                    }
                }
            }else{
                $data = DB::connection(config('app.URL_PGSQLGCP_IRK'))->insert("CALL editProfile(?,?,?,?,?,?,?)", [$nik,$nama,$nohp,$alias,$kelamin,$email,'']);

                    if($data) {

                        $imgpath = 'Dev/Ceritakita/Profile/'.$nik.'_'.$nama.'.';

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
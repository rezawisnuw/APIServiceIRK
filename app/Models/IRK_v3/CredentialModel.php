<?php

namespace App\Models\IRK_v3;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class CredentialModel extends Model
{

    private $status = 'Failed', $message = 'Data is cannot be process', $data = [];

    public function __construct(Request $request, $slug)
    {
        // Call the parent constructor
        //parent::__construct();

        $helper = new IRKHelper($request);
        $this->helper = $helper;

        $segment = $helper->Segment($slug);
        $this->connection = $segment['connection'];
        $this->credential = $segment['credential'];
        $this->path = $segment['path'];
    }

    public function showDataUserLogin($request)
    {

        $nik = $request['body']['nik'];
        $pass = $request['body']['pass'];

        try {
            $data = [];
            $data = $this->credential->select("select * from public.loginuser(?,?)", [$nik, $pass]);

            if(is_array($data)){
                if($data[0]->status == 'Login Berhasil' && !str_contains($data[0]->status,'menit')){
                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = $data[0]->status;
                }
            }

            $this->status;
            $this->message;
            $this->data;

        } catch (\Throwable $e) {
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }
}
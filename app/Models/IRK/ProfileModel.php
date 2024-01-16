<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class ProfileModel extends Model
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
        $this->path = $segment['path'];
    }

    public function showDataProfile($request)
    {

        $userid = $request['userid'];

        try {
            $data = $this->connection
                ->table('UserStatus')
                ->where('nik', '=', $userid)
                ->orderBy('log', 'desc')
                ->take(1)
                ->get()
                ->all();

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data;
            } else {
                $this->status;
                $this->message;
                $this->data;
            }


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

    public function showDataProfileSub($request)
    {
        $param['list_sp'] = array([
            'conn' => 'POR_DUMMY',
            'payload' => ['nik' => $request['userid']],
            'sp_name' => 'SP_GetAccessLevel',
            'process_name' => 'GetAccessLevelResult'
        ]);

        $response = $this->helper->SPExecutor($param);

        if ($response->status == 0) {
            return [
                'status' => $this->status,
                'data' => 'SPExecutor is cannot be process',
                'message' => $this->message
            ];
        } else {

            if (!empty($response->result->GetAccessLevelResult[0])) {
                $level = $response->result->GetAccessLevelResult[0]->role;

                if (str_contains($level, 'Admin') == false) {
                    return [
                        'status' => $this->status,
                        'data' => $level,
                        'message' => $this->message
                    ];
                }
            } else {
                $level = null;
            }

        }

        $rolelevel = str_contains($level, 'Admin') ? $level : base64_encode(microtime() . $request['userid']);
        $status = $request['status'];
        $kelamin = $request['kelamin'];
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $idjabatan = $request['idjabatan'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $iddepartemen = $request['iddepartemen'];

        try {
            $data = $this->connection->select("select * from showuserstatus(?,?,?,?,?,?,?,?,?)",
                [$rolelevel, $idjabatan, $idunit, $idcabang, $iddepartemen, $status, $kelamin, $periode1, $periode2]);

            $dataaktif = array_filter($data, function ($item) {
                return $item->akun == 'Active';
            });
            $dataterbatas = array_filter($data, function ($item) {
                return $item->akun == 'Inactive';
            });

            for ($index = 0; $index < count($data); $index++) {
                $data[$index]->aktifcurhatku = array_sum(array_column($dataaktif, 'ttlcurhatku'));
                $data[$index]->aktifideaku = array_sum(array_column($dataaktif, 'ttlideaku'));
                $data[$index]->aktifceritaku = array_sum(array_column($dataaktif, 'ttlceritaku'));
                $data[$index]->aktiflike = array_sum(array_column($dataaktif, 'ttllike'));
                $data[$index]->aktifcomment = array_sum(array_column($dataaktif, 'ttlcomment'));
                $data[$index]->aktifreport = array_sum(array_column($dataaktif, 'ttlreport'));
                $data[$index]->aktifremove = array_sum(array_column($dataaktif, 'ttlremove'));
                $data[$index]->aktifberilike = array_sum(array_column($dataaktif, 'rsplike'));
                $data[$index]->aktifbericomment = array_sum(array_column($dataaktif, 'rspcomment'));
                $data[$index]->aktifberireport = array_sum(array_column($dataaktif, 'rspreport'));

                $data[$index]->terbatascurhatku = array_sum(array_column($dataterbatas, 'ttlcurhatku'));
                $data[$index]->terbatasideaku = array_sum(array_column($dataterbatas, 'ttlideaku'));
                $data[$index]->terbatasceritaku = array_sum(array_column($dataterbatas, 'ttlceritaku'));
                $data[$index]->terbataslike = array_sum(array_column($dataterbatas, 'ttllike'));
                $data[$index]->terbatascomment = array_sum(array_column($dataterbatas, 'ttlcomment'));
                $data[$index]->terbatasreport = array_sum(array_column($dataterbatas, 'ttlreport'));
                $data[$index]->terbatasremove = array_sum(array_column($dataterbatas, 'ttlremove'));
                $data[$index]->terbatasberilike = array_sum(array_column($dataterbatas, 'rsplike'));
                $data[$index]->terbatasbericomment = array_sum(array_column($dataterbatas, 'rspcomment'));
                $data[$index]->terbatasberireport = array_sum(array_column($dataterbatas, 'rspreport'));
            }

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data;
            } else {
                $this->status;
                $this->message;
                $this->data;
            }

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

    public function inputDataProfile($request)
    {
        $param['list_sp'] = array([
            'conn' => 'POR_DUMMY',
            'payload' => ['nik' => $request['nik']],
            'sp_name' => 'SP_GetAccessLevel',
            'process_name' => 'GetAccessLevelResult'
        ]);

        $response = $this->helper->SPExecutor($param);

        if ($response->status == 0) {
            return [
                'status' => $this->status,
                'data' => 'SPExecutor is cannot be process',
                'message' => $this->message
            ];
        } else {
            if (!empty($response->result->GetAccessLevelResult[0])) {
                $level = $response->result->GetAccessLevelResult[0]->role;

                if (str_contains($level, 'Admin') == false) {
                    return [
                        'status' => $this->status,
                        'data' => $level,
                        'message' => $this->message
                    ];
                }
            } else {
                $level = null;
            }
        }

        $nik = $request['nik'];
        $nama = $request['nama'];
        $nohp = $request['nohp'];
        $alias = str_contains($level, 'Admin') ? $level : base64_encode(microtime() . $request['nik']);
        $email = $request['email'];
        $kelamin = $request['kelamin'];
        $status = empty($request['status']) ? 'Active' : $request['status'];
        $idjabatan = $request['idjabatan'];
        $jabatan = $request['jabatan'];
        $idunit = $request['idunit'];
        $unit = $request['unit'];
        $idcabang = $request['idcabang'];
        $cabang = $request['cabang'];
        $iddepartemen = $request['iddepartemen'];
        $departemen = $request['departemen'];
        $platform = $request['platform'];

        try {
            $data = $this->connection->insert("CALL inputuserstatus(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [$nik, $nama, $nohp, $platform == 'Website' ? $alias : base64_encode(microtime() . $nik), $email, $kelamin, $status, $idjabatan, $jabatan, $idunit, $unit, $idcabang, $cabang, $iddepartemen, $departemen, $platform]
            );

            if ($data) {
                $this->status = isset($request['userid']) && !empty($request['userid']) ? 'Success' : 'Processing';
                $this->message = 'Data has been process';
                $this->data = isset($request['userid']) && !empty($request['userid']) ? $data : $alias;
            } else {
                $this->status;
                $this->message;
                $this->data;
            }

        } catch (\Exception $e) {
            $this->status;
            $this->data;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

}
<?php

namespace App\Models\IRK_v2;

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
            $data = [];
            $data = $this->connection
                ->table('public_v2.UserStatus')
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

    public function showDataProfileSubUser($request)
    {
        $param['list_sp'] = array(
            [
                'conn' => 'POR_DUMMY',
                'payload' => ['nik' => $request['userid']],
                'sp_name' => 'SP_GetAccessLevel',
                'process_name' => 'GetAccessLevelResult'
            ]
        );

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
        $idjabatan = $request['idjabatan'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $iddepartemen = $request['iddepartemen'];
        $employee = $request['employee'];

        try {
            $data = [];
            $data = $this->connection->select(
                "select * from public_v2.showuserstatusprofile(?,?,?,?,?,?,?,?,?)",
                [$rolelevel, $idjabatan, $idunit, $idcabang, $iddepartemen, $status, $employee]
            );

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
        $param['list_sp'] = array(
            [
                'conn' => 'POR_DUMMY',
                'payload' => ['nik' => $request['nik']],
                'sp_name' => 'SP_GetAccessLevel',
                'process_name' => 'GetAccessLevelResult'
            ]
        );

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
        $iddirektorat = $request['iddirektorat'];
        $direktorat = $request['direktorat'];
        $iddivisi = $request['iddivisi'];
        $divisi = $request['divisi'];
        $platform = $request['platform'];

        try {
            $data = $this->connection->insert(
                "CALL public_v2.inputuserstatus(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [$nik, $nama, $nohp, $platform == 'Website' ? $alias : base64_encode(microtime() . $nik), $email, $kelamin, $status, $idjabatan, $jabatan, $idunit, $unit, $idcabang, $cabang, $iddepartemen, $departemen, $iddirektorat, $direktorat, $iddivisi, $divisi, $platform]
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

    public function editDataUserStatusProfile($request)
    {

        $userid = $request['userid'];
        $nik = $request['nik'];
        $userstatus = $request['userstatus'];

        try {
            $data = $this->connection->insert("CALL public_v2.edituserstatus(?,?,?)", [$userid, $nik, $userstatus]);

            if ($data) {
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

}
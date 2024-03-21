<?php

namespace App\Models\IRK_v2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class FilemanagerModel extends Model
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
        $this->config = $segment['config'];
    }

    public function showDataUserStatus($request)
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
        $statuspengguna = $request['statuspengguna'];
        $kelamin = $request['kelamin'];
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $idjabatan = $request['idjabatan'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $iddepartemen = $request['iddepartemen'];

        try {
            $data = [];
            $data = $this->connection->select(
                "select * from public_v2.showuserstatusreport(?,?,?,?,?,?,?,?,?)",
                [$rolelevel, $idjabatan, $idunit, $idcabang, $iddepartemen, $statuspengguna, $kelamin, $periode1, $periode2]
            );

            $dataaktif = array_filter($data, function ($item) {
                return $item->status_pengguna == 'Active';
            });
            $dataterbatas = array_filter($data, function ($item) {
                return $item->status_pengguna == 'Inactive';
            });

            for ($index = 0; $index < count($data); $index++) {
                $data[$index]->aktifcurhatku = array_sum(array_column($dataaktif, 'ttlcurhatku'));
                $data[$index]->aktifideaku = array_sum(array_column($dataaktif, 'ttlideaku'));
                $data[$index]->aktifceritaku = array_sum(array_column($dataaktif, 'ttlceritaku'));
                $data[$index]->aktiflike = array_sum(array_column($dataaktif, 'ttllike'));
                $data[$index]->aktifcomment = array_sum(array_column($dataaktif, 'ttlcomment'));
                $data[$index]->aktifnewcomment = array_sum(array_column($dataaktif, 'ttlnewcomment'));
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
                $data[$index]->terbatasnewcomment = array_sum(array_column($dataterbatas, 'ttlnewcomment'));
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

    public function showDataCeritakuIdeaku($request)
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
        $statuspengguna = $request['statuspengguna'];
        $statuskonten = $request['statuskonten'];
        $statusdilaporkan = $request['statusdilaporkan'];
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $kategori = $request['kategori'];

        try {
            $data = [];
            $data = $this->connection->select(
                "select * from public_v2.showceritakuideakureport(?,?,?,?,?,?,?,?,?)",
                [$rolelevel, $idunit, $idcabang, $statuspengguna, $statuskonten, $statusdilaporkan, $kategori, $periode1, $periode2]
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

    public function showDataCurhatku($request)
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
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $iddepartemen = $request['iddepartemen'];

        try {
            $data = [];
            $data = $this->connection->select(
                "select * from public_v2.showcurhatkureport(?,?,?,?,?,?)",
                [$rolelevel, $idunit, $idcabang, $iddepartemen, $periode1, $periode2]
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

    public function showDataTotalActivity($request)
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

        if ($request['idunit'] = 'ALL' && $request['idcabang'] = 'ALL') {
            $unit = [
                'code' => '1',
                'nik' => $request['userid'],
            ];

            $client = new Client();
            $response = $client->post(
                'http://' . $this->config . '/RESTSecurity/RESTSecurity.svc/IDM/Unit-Cabang',
                [
                    RequestOptions::JSON =>
                        ['param' => $unit]
                ]
            );
            $body = $response->getBody();
            $temp = json_decode($body);
            $resultunit = json_decode($temp->UnitCabangResult);

            foreach ($resultunit as $k_unit => $v_unit) {
                $param_unit[$k_unit] = $v_unit->AliasUnit;
            }

            $cabang = [
                'code' => '2',
                'nik' => $request['userid'],
            ];

            $client = new Client();
            $response = $client->post(
                'http://' . $this->config . '/RESTSecurity/RESTSecurity.svc/IDM/Unit-Cabang',
                [
                    RequestOptions::JSON =>
                        ['param' => $cabang]
                ]
            );
            $body = $response->getBody();
            $temp = json_decode($body);
            $resultcabang = json_decode($temp->UnitCabangResult);

            foreach ($resultcabang as $k_cabang => $v_cabang) {
                $param_cabang[$k_cabang] = $v_cabang->AREAID;
            }

        } else if ($request['idunit'] = 'ALL' && $request['idcabang'] != 'ALL') {
            $exportdata = [
                'code' => '9',
                'nik' => $request['userid'],
                'id_unit' => $request['idunit'],
                'id_cabang' => $request['idcabang']
            ];
        } else if ($request['idunit'] != 'ALL' && $request['idcabang'] = 'ALL') {
            $exportdata = [
                'code' => '9',
                'nik' => $request['userid'],
                'id_unit' => $request['idunit'],
                'id_cabang' => $request['idcabang']
            ];
        }

        $client = new Client();
        $response = $client->post(
            'http://' . $this->config . '/RESTSecurity/RESTSecurity.svc/IDM/Unit-Cabang',
            [
                RequestOptions::JSON =>
                    ['param' => $exportdata]
            ]
        );

        $body = $response->getBody();
        $temp = json_decode($body);
        $result = json_decode($temp->UnitCabangResult);

        $rolelevel = str_contains($level, 'Admin') ? $level : base64_encode(microtime() . $request['userid']);
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $idunit = $request['idunit'];
        $idcabang = $request['idcabang'];
        $iddepartemen = $request['iddepartemen'];
        $direktorat = $result;

        try {
            $data = [];
            $data = $this->connection->select(
                "select * from public_v2.showttlactivityreport(?,?,?,?,?,?,?)",
                [$rolelevel, $idunit, $idcabang, $iddepartemen, $direktorat, $periode1, $periode2]
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
}
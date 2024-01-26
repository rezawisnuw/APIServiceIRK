<?php

namespace App\Models\IRK_v2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class ReportModel extends Model
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

    public function showDataReportTicket($request)
    {
        $idticket = $request['idticket'];
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$idticket, $userid]);

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

    public function showDataReportComment($request)
    {
        $idcomment = $request['idcomment'];
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showreportcomment(?,?)", [$idcomment, $userid]);

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

    public function inputDataReportTicket($request)
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

                $activity = $this->connection
                    ->table('UserStatus')
                    ->select('platforms')
                    ->where('nik', '=', $request['nik'])
                    ->orderBy('log', 'desc')
                    ->take(1)
                    ->get();

                $platform = $activity[0]->platforms;

            } else {
                $level = null;
            }
        }

        $nik = $request['nik'];
        $report = $request['report'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);

        try {
            $data = $this->connection->insert("CALL public.inputreportticket(?,?,?,?,?,?)", [$nik, $report, $idticket, $tag, $alias, $platform]);

            if ($data) {

                $target = $this->connection
                    ->table('CeritaKita')
                    ->select('employee', 'tag')
                    ->where('id_ticket', '=', $idticket)
                    ->get()[0];

                $target->idticket = ["idticket" => $idticket];

                $toJson = json_encode($target->idticket);

                $toBase64 = base64_encode($toJson);

                $body['data'] = [
                    'nik' => $target->employee,
                    'apps' => 'Web Admin IRK',
                    'nikLogin' => $nik,
                    'shortMessage' => 'Report Content ' . $target->tag,
                    'longMessage' => 'Random alias melaporkan postingan anda',
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $$toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
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

    public function inputDataReportComment($request)
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

                $activity = $this->connection
                    ->table('UserStatus')
                    ->select('platforms')
                    ->where('nik', '=', $request['nik'])
                    ->orderBy('log', 'desc')
                    ->take(1)
                    ->get();

                $platform = $activity[0]->platforms;

            } else {
                $level = null;
            }
        }

        $nik = $request['nik'];
        $report = $request['report'];
        $idcomment = $request['idcomment'];
        $tag = $request['tag'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);

        try {
            $data = $this->connection->insert("CALL public.inputreportcomment(?,?,?,?,?,?)", [$nik, $report, $idcomment, $tag, $alias, $platform]);

            if ($data) {

                $target = $this->connection
                    ->table('Comment')
                    ->select('nik_karyawan', 'tag', 'id_ticket')
                    ->where('id_comment', '=', $idcomment)
                    ->get()[0];

                $toJson = json_encode($target->idticket);

                $toBase64 = base64_encode($toJson);

                $body['data'] = [
                    'nik' => $target->nik_karyawan,
                    'apps' => 'Web Admin IRK',
                    'nikLogin' => $nik,
                    'shortMessage' => 'Report Comment ' . $target->tag,
                    'longMessage' => 'Random alias melaporkan komentar anda',
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
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
<?php

namespace App\Models\IRK_v3;

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
            $data = $this->connection->select("select * from public_v3.showreportticket(?,?)", [$idticket, $userid]);

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
            $data = $this->connection->select("select * from public_v3.showreportcomment(?,?)", [$idcomment, $userid]);

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
        $activity = $this->connection
            ->table('public_v3.UserStatus')
            ->select('platforms')
            ->where('nik', '=', $request['nik'])
            ->orderBy('log', 'desc')
            ->take(1)
            ->get();

        $platform = $activity[0]->platforms;

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
        $report = $request['report'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);

        try {
            $data = $this->connection->insert("CALL public_v3.inputreportticket(?,?,?,?,?,?)", [$nik, $report, $idticket, $tag, $alias, $platform]);

            if ($data) {

                $target = $this->connection
                    ->table('public_v3.CeritaKita')
                    ->select('employee', 'tag', 'is_used')
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
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $idticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$idticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$idticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$idticket])[0]->getttlnewcomment;

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
                $this->data = ["blocked" => $target->is_used == "Yes" ? true : false, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
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
        $activity = $this->connection
            ->table('public_v3.UserStatus')
            ->select('platforms')
            ->where('nik', '=', $request['nik'])
            ->orderBy('log', 'desc')
            ->take(1)
            ->get();

        $platform = $activity[0]->platforms;

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
        $report = $request['report'];
        $idcomment = $request['idcomment'];
        // $idreply = $request['idreply'];
        // $idparent = $request['idparent'];
        // $parentreply = $request['parentreply'];
        $tag = $request['tag'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);

        try {
            // $data = $this->connection->insert("CALL public_v3.inputreportcomment(?,?,?,?,?,?,?,?,?)", [$nik, $report, $idcomment, $idreply, $idparent, $parentreply, $tag, $alias, $platform]);
            $data = $this->connection->insert("CALL public_v3.inputreportcomment(?,?,?,?,?,?)", [$nik, $report, $idcomment, $tag, $alias, $platform]);

            if ($data) {

                // $target = $this->connection
                //     ->table('public_v3.Comment')
                //     ->select('nik_karyawan', 'tag', 'id_ticket')
                //     ->where('id_comment', '=', $parentreply == 0 ? $idcomment : $idparent)
                //     ->get()[0];

                $target = $this->connection
                    ->table('public_v3.NewComment')
                    ->select('nik_karyawan', 'tag', 'id_ticket', 'is_blocked')
                    ->where('id_comment', '=', $idcomment)
                    ->get()[0];

                $transit = $this->connection
                    ->table('public_v2.CeritaKita')
                    ->select('employee', 'tag', 'is_used')
                    ->where('id_ticket', '=', $target->id_ticket)
                    ->get()[0];
                
                $toJson = json_encode($target->id_ticket);

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

                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $target->id_ticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$target->id_ticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$target->id_ticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$target->id_ticket])[0]->getttlnewcomment;

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
                $this->data = ["blocked" => $transit->is_used == 'No' ? $target->is_blocked : true, "blocked_comment" => $transit->is_used == 'Yes' ? false : $target->is_blocked, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];

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
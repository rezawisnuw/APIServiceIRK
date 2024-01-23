<?php

namespace App\Models\IRK_v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class CommentModel extends Model
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

    public function showDataCommentTotal($request)
    {
        $idticket = $request['idticket'];
        $userid = $request['userid'];

        try {

            $data = $this->connection->select("select * from public.showcomment(?,?)", [$idticket, $userid]);

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

    public function inputDataComment($request)
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
        $comment = $request['comment'];
        $idticket = $request['idticket'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
        $tag = $request['tag'];

        try {

            $data = $this->connection->insert("CALL public.inputcomment(?,?,?,?,?)", [$nik, $comment, $idticket, $alias, $tag]);

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
                    'shortMessage' => 'Comment ' . $target->tag,
                    'longMessage' => 'Random alias mengomentari postingan anda',
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

    public function inputDataReplyComment($request)
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
        $comment = $request['comment'];
        $idreply = $request['idreply'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
        $parentreply = $request['parentreply'];

        try {
            $data = $this->connection->insert("CALL public.inputreplycomment(?,?,?,?,?)", [$nik, $comment, $idreply, $alias, $parentreply]);

            if ($data) {
                $target = $this->connection
                    ->table('Comment')
                    ->select('tag', 'nik_karyawan', 'id_ticket')
                    ->where($parentreply == 0 ? 'id_comment' : 'id_reply_comment', '=', $idreply)
                    ->get()[0];

                $toJson = json_encode($target->id_ticket);

                $toBase64 = base64_encode($toJson);

                $body['data'] = [
                    'nik' => $target->nik_karyawan,
                    'apps' => 'Web Admin IRK',
                    'nikLogin' => $nik,
                    'shortMessage' => 'Reply Comment ' . $target->tag,
                    'longMessage' => 'Random alias membalas komentar anda',
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktifasi izin notifikasi pada browser anda terlebih dahulu';
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

    public function editDataComment($request)
    {

        $nik = $request['nik'];
        $idcomment = $request['idcomment'];
        $tag = $request['tag'];

        try {
            $data = $this->connection->insert("CALL public.editcomment(?,?,?)", [$nik, $idcomment, $tag]);

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

    public function editDataReplyComment($request)
    {

        $nik = $request['nik'];
        $idreplycomment = $request['idreplycomment'];

        try {
            $data = $this->connection->insert("CALL public.editreplycomment(?,?)", [$nik, $idreplycomment]);

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
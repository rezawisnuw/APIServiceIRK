<?php

namespace App\Models\IRK_v3;

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

    // public function showDataComment($request)
    // {
    //     $idticket = $request['idticket'];
    //     $userid = $request['userid'];

    //     try {
    //         $data = [];
    //         $data = $this->connection->select("select * from public_v3.showcomment(?,?)", [$idticket, $userid]);

    //         if (is_array($data)) {
    //             $this->status = 'Success';
    //             $this->message = 'Data has been process';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    // public function showDataReplyComment($request)
    // {
    //     $idcomment = $request['idcomment'];
    //     //$idreply = $request['idreply'];
    //     $parentreply = $request['parentreply'];
    //     $userid = $request['userid'];

    //     try {
    //         $data = [];
    //         $data = $this->connection->select("select * from public_v3.showreplycomment(?,?,?)", [$idcomment, $parentreply, $userid]);
    //         //$data = $this->connection->select("select * from public_v3.showreplycomment(?,?,?,?)", [$idcomment, $idreply, $parentreply, $userid]);

    //         if (is_array($data)) {
    //             $this->status = 'Success';
    //             $this->message = 'Data has been process';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    // public function inputDataComment($request)
    // {
    //     $param['list_sp'] = array(
    //         [
    //             'conn' => 'POR_DUMMY',
    //             'payload' => ['nik' => $request['nik']],
    //             'sp_name' => 'SP_GetAccessLevel',
    //             'process_name' => 'GetAccessLevelResult'
    //         ]
    //     );

    //     $response = $this->helper->SPExecutor($param);

    //     if ($response->status == 0) {
    //         return [
    //             'status' => $this->status,
    //             'data' => 'SPExecutor is cannot be process',
    //             'message' => $this->message
    //         ];
    //     } else {
    //         if (!empty($response->result->GetAccessLevelResult[0])) {
    //             $level = $response->result->GetAccessLevelResult[0]->role;

    //             if (str_contains($level, 'Admin') == false) {
    //                 return [
    //                     'status' => $this->status,
    //                     'data' => $level,
    //                     'message' => $this->message
    //                 ];
    //             }

    //             $activity = $this->connection
    //                 ->table('public_v3.UserStatus')
    //                 ->select('platforms')
    //                 ->where('nik', '=', $request['nik'])
    //                 ->orderBy('log', 'desc')
    //                 ->take(1)
    //                 ->get();

    //             $platform = $activity[0]->platforms;

    //         } else {
    //             $level = null;
    //         }
    //     }

    //     $nik = $request['nik'];
    //     $comment = $request['comment'];
    //     $idticket = $request['idticket'];
    //     $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
    //     $tag = $request['tag'];

    //     try {

    //         $data = $this->connection->insert("CALL public_v3.inputcomment(?,?,?,?,?,?)", [$nik, $comment, $idticket, $alias, $tag, $platform]);

    //         if ($data) {

    //             $target = $this->connection
    //                 ->table('public_v3.CeritaKita')
    //                 ->select('employee', 'tag')
    //                 ->where('id_ticket', '=', $idticket)
    //                 ->get()[0];

    //             $target->idticket = ["idticket" => $idticket];

    //             $toJson = json_encode($target->idticket);

    //             $toBase64 = base64_encode($toJson);

    //             $body['data'] = [
    //                 'nik' => $target->employee,
    //                 'apps' => 'Web Admin IRK',
    //                 'nikLogin' => $nik,
    //                 'shortMessage' => 'Comment ' . $target->tag,
    //                 'longMessage' => 'Random alias mengomentari postingan anda',
    //                 'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
    //             ];

    //             $response = $this->helper->NotificationPortal($body);

    //             $this->status = 'Success';
    //             $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    // public function inputDataReplyComment($request)
    // {
    //     $param['list_sp'] = array(
    //         [
    //             'conn' => 'POR_DUMMY',
    //             'payload' => ['nik' => $request['nik']],
    //             'sp_name' => 'SP_GetAccessLevel',
    //             'process_name' => 'GetAccessLevelResult'
    //         ]
    //     );

    //     $response = $this->helper->SPExecutor($param);

    //     if ($response->status == 0) {
    //         return [
    //             'status' => $this->status,
    //             'data' => 'SPExecutor is cannot be process',
    //             'message' => $this->message
    //         ];
    //     } else {
    //         if (!empty($response->result->GetAccessLevelResult[0])) {
    //             $level = $response->result->GetAccessLevelResult[0]->role;

    //             if (str_contains($level, 'Admin') == false) {
    //                 return [
    //                     'status' => $this->status,
    //                     'data' => $level,
    //                     'message' => $this->message
    //                 ];
    //             }

    //             $activity = $this->connection
    //                 ->table('public_v3.UserStatus')
    //                 ->select('platforms')
    //                 ->where('nik', '=', $request['nik'])
    //                 ->orderBy('log', 'desc')
    //                 ->take(1)
    //                 ->get();

    //             $platform = $activity[0]->platforms;

    //         } else {
    //             $level = null;
    //         }
    //     }

    //     $nik = $request['nik'];
    //     $comment = $request['comment'];
    //     $idreply = $request['idreply'];
    //     $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
    //     $parentreply = $request['parentreply'];
    //     $idcomment = $request['idcomment'];

    //     try {

    //         $data = $this->connection->insert("CALL public_v3.inputreplycomment(?,?,?,?,?,?,?)", [$nik, $comment, $idreply, $idcomment, $alias, $parentreply, $platform]);

    //         if ($data) {
    //             if ($parentreply == 0) {
    //                 $target = $this->connection
    //                     ->table('public_v3.Comment')
    //                     ->select('tag', 'nik_karyawan', 'id_ticket')
    //                     ->where('id_comment', '=', $idreply)
    //                     ->get()[0];
    //             } else {
    //                 // $temptarget = $this->connection
    //                 //     ->table('public_v3.ReplyComment')
    //                 //     ->select('id_reply_comment', 'reply_to_id', 'parent_comment', 'id_parent_comment')
    //                 //     ->where('id_reply_comment', '=', $idreply)
    //                 //     ->where('parent_comment', '=', $parentreply - 1)
    //                 //     ->where('reply_to_id', '=', $idcomment)
    //                 //     ->get()[0];

    //                 // $target = $this->connection
    //                 //     ->table('public_v3.Comment')
    //                 //     ->select('tag', 'nik_karyawan', 'id_ticket')
    //                 //     ->where('id_comment', '=', $temptarget->reply_to_id)
    //                 //     ->get()[0];

    //                 $target = $this->connection
    //                     ->table('public_v3.Comment')
    //                     ->select('tag', 'nik_karyawan', 'id_ticket')
    //                     ->where('id_comment', '=', $idcomment)
    //                     ->get()[0];
    //             }


    //             $toJson = json_encode($target->id_ticket);

    //             $toBase64 = base64_encode($toJson);

    //             $body['data'] = [
    //                 'nik' => $target->nik_karyawan,
    //                 'apps' => 'Web Admin IRK',
    //                 'nikLogin' => $nik,
    //                 'shortMessage' => 'Reply Comment ' . $target->tag,
    //                 'longMessage' => 'Random alias membalas komentar anda',
    //                 'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
    //             ];

    //             $response = $this->helper->NotificationPortal($body);

    //             $this->status = 'Success';
    //             $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktifasi izin notifikasi pada browser anda terlebih dahulu';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    // public function editDataComment($request)
    // {

    //     $nik = $request['nik'];
    //     $idcomment = $request['idcomment'];
    //     $tag = $request['tag'];

    //     try {
    //         $data = $this->connection->insert("CALL public_v3.editcomment(?,?,?)", [$nik, $idcomment, $tag]);

    //         if ($data) {
    //             $this->status = 'Success';
    //             $this->message = 'Data has been process';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    // public function editDataReplyComment($request)
    // {

    //     $nik = $request['nik'];
    //     $idcomment = $request['idcomment'];
    //     $idreplycomment = $request['idreplycomment'];
    //     $idreply = $request['idreply'];
    //     $parentcomment = $request['parentcomment'];

    //     try {
    //         $data = $this->connection->insert("CALL public_v3.editreplycomment(?,?,?,?,?)", [$nik, $idcomment, $idreplycomment, $idreply, $parentcomment]);

    //         if ($data) {
    //             $this->status = 'Success';
    //             $this->message = 'Data has been process';
    //             $this->data = $data;
    //         } else {
    //             $this->status;
    //             $this->message;
    //             $this->data;
    //         }

    //     } catch (\Throwable $e) {
    //         $this->status = 'Error';
    //         $this->data = null;
    //         $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
    //     }

    //     return [
    //         'status' => $this->status,
    //         'data' => $this->data,
    //         'message' => $this->message
    //     ];
    // }

    public function showDataNewComment($request)
    {
        $idticket = $request['idticket'];
        $parentreply = $request['parentreply'];
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v3.shownewcomment(?,?,?)", [$idticket, $parentreply, $userid]);

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

    public function showDataReplyNewComment($request)
    {
        $idcomment = $request['idcomment'];
        $idticket = $request['idticket'];
        $parentreply = $request['parentreply'];
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v3.showreplynewcomment(?,?,?,?)", [$idcomment, $idticket, $parentreply, $userid]);

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

    public function inputDataNewComment($request)
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
        $comment = $request['comment'];
        $idticket = $request['idticket'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
        $parentreply = $request['parentreply'];
        $tag = $request['tag'];

        try {

            $data = $this->connection->insert("CALL public_v3.inputnewcomment(?,?,?,?,?,?,?)", [$nik, $comment, $idticket, $alias, $tag, $parentreply, $platform]);

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
                    'shortMessage' => 'Comment ' . $target->tag,
                    'longMessage' => 'Random alias mengomentari postingan anda',
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

    public function inputDataReplyNewComment($request)
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
        $comment = $request['comment'];
        $idticket = $request['idticket'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
        $parentreply = $request['parentreply'];
        $tag = $request['tag'];

        try {

            $data = $this->connection->insert("CALL public_v3.inputreplynewcomment(?,?,?,?,?,?,?)", [$nik, $comment, $idticket, $alias, $tag, $platform, $parentreply]);

            if ($data) {
                $target = $this->connection
                    ->table('public_v3.NewComment')
                    ->select('tag', 'nik_karyawan', 'id_ticket', 'is_blocked')
                    ->where('id_ticket', '=', $idticket)
                    ->where('parentreply', '=', $parentreply)
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

                
                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $target->id_ticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$target->id_ticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$target->id_ticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$target->id_ticket])[0]->getttlnewcomment;
                

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktifasi izin notifikasi pada browser anda terlebih dahulu';
                $this->data = ["blocked" => $target->is_blocked, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
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

    public function editDataNewComment($request)
    {
        $nik = $request['nik'];
        $idcomment = $request['idcomment'];
        $status = $request['status'];

        try {
            $data = $this->connection->insert("CALL public_v3.update_comment(?,?,?)", [$nik, $status, $idcomment]);
            //$data = $this->connection->insert("CALL public_v3.editnewcomment(?,?)", [$nik, $idcomment]);
            if ($data) {
                // $this->status = 'Success';
                // $this->message = 'Data has been process';
                // $this->data = $data;

                $target = $this->connection
                    ->table('public_v3.NewComment')
                    ->select('tag', 'nik_karyawan', 'id_ticket', 'is_blocked')
                    ->where('id_comment', '=', $idcomment)
                    ->get()[0];

                $toJson = json_encode($target->id_ticket);

                $toBase64 = base64_encode($toJson);

                $body['data'] = [
                    'nik' => $target->nik_karyawan,
                    'apps' => 'Web Admin IRK',
                    'nikLogin' => $nik,
                    'shortMessage' => 'Comment ' . $target->tag,
                    'longMessage' => 'Random alias menghapus komentar anda',
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                
                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $target->id_ticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$target->id_ticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$target->id_ticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$target->id_ticket])[0]->getttlnewcomment;
                

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktifasi izin notifikasi pada browser anda terlebih dahulu';
                $this->data = ["blocked" => $target->is_blocked, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
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

    public function editDataReplyNewComment($request)
    {
        $nik = $request['nik'];
        $idcomment = $request['idcomment'];

        try {
            $data = $this->connection->insert("CALL public_v3.editreplynewcomment(?,?)", [$nik, $idcomment]);

            if ($data) {
                // $this->status = 'Success';
                // $this->message = 'Data has been process';
                // $this->data = $data;

                $target = $this->connection
                    ->table('public_v3.NewComment')
                    ->select('tag', 'nik_karyawan', 'id_ticket', 'is_blocked')
                    ->where('id_comment', '=', $idcomment)
                    ->get()[0];

                $toJson = json_encode($target->id_ticket);

                $toBase64 = base64_encode($toJson);

                $body['data'] = [
                    'nik' => $target->nik_karyawan,
                    'apps' => 'Web Admin IRK',
                    'nikLogin' => $nik,
                    'shortMessage' => 'Reply Comment ' . $target->tag,
                    'longMessage' => 'Random alias menghapus komentar anda',
                    'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                ];

                $response = $this->helper->NotificationPortal($body);

                
                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $target->id_ticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$target->id_ticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$target->id_ticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$target->id_ticket])[0]->getttlnewcomment;
                

                $this->status = 'Success';
                $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktifasi izin notifikasi pada browser anda terlebih dahulu';
                $this->data = ["blocked" => $target->is_blocked, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
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
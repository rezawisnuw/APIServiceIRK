<?php

namespace App\Models\IRK_v3;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class LikeModel extends Model
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

    public function showDataLike($request)
    {
        $idticket = $request['idticket'];
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v3.showlike(?,?)", [$idticket, $userid]);

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

    public function inputDataLike($request)
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
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['nik']);
        $userlike = $request['userlike'];

        try {
            $data = $this->connection->insert("CALL public_v3.inputlike(?,?,?,?,?,?)", [$nik, $idticket, $tag, $alias, $userlike, $platform]);

            if ($data) {

                $target = $this->connection
                    ->table('public_v3.CeritaKita')
                    ->select('CeritaKita.employee AS employee', 'CeritaKita.tag AS tag', 'CeritaKita.is_used AS is_used', 'Likes.like AS like')
                    ->leftJoin('public_v3.Likes', 'Likes.id_ticket', '=', 'CeritaKita.id_ticket')
                    ->where('Likes.id_ticket', '=', $idticket)
                    ->where('Likes.nik_karyawan', '=', $nik)
                    ->get()[0];

                $target->idticket = ["idticket" => $idticket];

                $toJson = json_encode($target->idticket);

                $toBase64 = base64_encode($toJson);
                
                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $idticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$idticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$idticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$idticket])[0]->getttlnewcomment;

                if ($target->like == 1) {
                    $body['data'] = [
                        'nik' => $target->employee,
                        'apps' => 'Web Admin IRK',
                        'nikLogin' => $nik,
                        'shortMessage' => 'Like ' . $target->tag,
                        'longMessage' => 'Random alias menyukai postingan anda',
                        'link' => 'portal/irk/transaksi/cerita-kita/rincian/redirect/' . $toBase64
                    ];

                    $response = $this->helper->NotificationPortal($body);

                    $this->status = 'Success';
                    $this->message = $response->Result->status == 1 ? $response->Result->message : 'Silahkan periksa aktivasi izin notifikasi pada browser anda terlebih dahulu';
                    $this->data = ["blocked" => $target->is_used == "Yes" ? true : false, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
                } else {
                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = ["blocked" => $target->is_used == "Yes" ? true : false, "likedby" => $likedby, "ttllike" => $ttllike, "ttlcomment" => $ttlcomment, "ttlnewcomment" => $ttlnewcomment];
                }
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
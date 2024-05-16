<?php

namespace App\Models\IRK_v3;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class NotificationuserModel extends Model
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


    public function showDataNotificationUser($request)
    {
        try {

            $nik = $request['data']['nik'] ?? null;
            $data = $this->connection->select('SELECT * FROM public_v3.get_notificationuser(?)', [$nik]);

            if ($data) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data;
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


    public function inputDataNotificationUser($request)
    {
        try {
            $hub_id = $request->input('hub_id');
            $nik = $request->input('nik');

            $this->connection->select('CALL public_v3.input_NotificationUser(?, ?)', [$hub_id, $nik]);

            return [
                'status' => 'Success',
                'data' => null,
                'message' => 'Notification user added successfully'
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'Error',
                'data' => null,
                'message' => $e->getMessage()
            ];
        }
    }

    public function editDataNotificationUser($request)
    {
        $notification_userid = isset($request['data']['notification_userid']) ? $request['data']['notification_userid'] : null;
        $action = $request['data']['action'];
        $nik = $request['data']['nik'];

        try {
            $sql = "CALL public_v3.edit_notificationuser(?, ?, ?, ?)";

            // Sesuaikan parameter yang dikirimkan berdasarkan kondisi
            if ($action === 'lihat' && $notification_userid !== "") {
                $data = $this->connection->select($sql, [$notification_userid, $action, $nik, null]);
            } elseif ($action === 'tandai_semua_dibaca') {
                $data = $this->connection->select($sql, [null, $action, $nik, null]);
                $data = $this->connection->select('SELECT * FROM public_v3.get_notificationuser(?)', [$nik]);
                // Jika tindakan adalah 'tandai_semua_dibaca', tidak ada data yang perlu diproses
                return [
                    'status' => 'Success',
                    'data' => $data,
                    'message' => 'All notifications marked as read successfully'
                ];
            } else {
                return [
                    'status' => 'Error',
                    'data' => null,
                    'message' => 'Invalid action or conditions'
                ];
            }

            if (is_array($data) && count($data) > 0) {
                $result_variable = json_decode($data[0]->result_variable, true);

                // Lakukan pemrosesan data sesuai dengan kebutuhan Anda
                $formatted_data = [
                    "idticket" => $result_variable['idticket'],
                    "employee" => $result_variable['employee'],
                    "header" => $result_variable['header'],
                    "text" => $result_variable['text'],
                    "picture" => $result_variable['picture'],
                    "key" => $result_variable['key'],
                    "created" => $result_variable['created'],
                    "alias" => $result_variable['alias'],
                    "ttlcomment" => $result_variable['ttlcomment'],
                    "ttlnewcomment" => $result_variable['ttlnewcomment'],
                    "ttllike" => $result_variable['ttllike'],
                    "likeby" => $result_variable['likeby'],
                    "blocked" => $result_variable['blocked'],
                    "reasonblocked" => $result_variable['reasonblocked'],
                    "followup" => $result_variable['followup'],
                    //"postingby" => $result_variable['postingby'],
                    "userflag" => $result_variable['userflag'],
                    "employeename" => $result_variable['employeename'],
                    "jabatan" => $result_variable['jabatan'],
                    "kodejabatan" => $result_variable['kodejabatan'],
                    "namacabang" => $result_variable['namacabang'],
                    "idcabang" => $result_variable['idcabang'],
                    "namadepartemen" => $result_variable['namadepartemen'],
                    "iddepartemen" => $result_variable['iddepartemen'],
                    "lastloginby" => $result_variable['lastloginby'],
                    "viewer" => $result_variable['viewer'],
                    "is_bookmark" => $result_variable['is_bookmark']
                ];

                $userid = $request['data']['nik'];
                // Periksa apakah properti 'comments' tersedia sebelum mencoba mengaksesnya
                $comments = json_decode($this->connection->select("select * from public_v3.get_comments_temp(?,?)", [$formatted_data['idticket'], $userid])[0]->comments);

                if (count($comments) >= 1 && !empty($comments[0]->id_comment)) {
                    $formatted_data['comments'] = $comments;
                } else {
                    $formatted_data['comments'] = [];
                }

                $formatted_data['likes'] = $this->connection->select("select * from public_v3.showlike(?,?)", [$formatted_data['idticket'], $userid]);

                $formatted_data['report_ticketlist'] = $this->connection->select("select * from public_v3.showreportticket(?,?)", [$formatted_data['idticket'], $userid]);
                $formatted_data['report_ticket'] = count($formatted_data['report_ticketlist']) > 0 ? 'Ya' : 'Tidak';

                return [
                    'status' => 'Success',
                    'data' => $formatted_data,
                    'message' => 'Notification user edited successfully'
                ];
            } else {
                return [
                    'status' => 'Error',
                    'data' => null,
                    'message' => 'No data found'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status' => 'Error',
                'data' => null,
                'message' => $e->getMessage()
            ];
        }
    }

}
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
            //$data = $this->connection->select('SELECT * FROM public_v3.get_notificationuser(?)', [$nik]);
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

            //$this->connection->select('CALL public_v3.input_NotificationUser(?, ?)', [$hub_id, $nik]);
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

            //$data = $this->connection->insert('CALL public_v3.edit_NotificationUser(?, ?, ?)', [$hub_id, $action, $nik]);
            $data = $this->connection->insert('CALL public_v3.edit_NotificationUser(?, ?, ?)', [$notification_userid, $action, $nik]);

            return [
                'status' => 'Success',
                'data' => null,
                'message' => 'Notification user edited successfully'
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'Error',
                'data' => null,
                'message' => $e->getMessage()
            ];
        }
    }
}
<?php

namespace App\Models\IRK_v2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class CeritakuModel extends Model
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

    public function showDataCeritaku($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showceritakulist(?,?)", [$userid, $page]);

            if (is_array($data)) {
                $this->status = $page != 0 ? 'Processing' : 'Success';
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

        for ($index = 0; $index < count($data); $index++) {
            $comments = json_decode($this->connection->select("select * from public_v2.get_comments_temp(?,?)", [$data[$index]->idticket, $userid])[0]->comments);

            if (count($comments) >= 1 && !empty($comments[0]->id_comment)) {
                $data[$index]->comments = $comments;
            } else {
                $data[$index]->comments = [];
            }
            if (!isset($data[$index]->likes)) {
                $data[$index]->likes = [];
            }
            $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
            if (!isset($data[$index]->report_ticketlist)) {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
            $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataCeritakuSingle($request)
    {
        $userid = $request['userid'];
        $idticket = $request['idticket'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showceritakudetail(?,?)", [$userid, $idticket]);

            if (is_array($data)) {
                $this->status = 'Processing';
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

        for ($index = 0; $index < count($data); $index++) {
            $comments = json_decode($this->connection->select("select * from public_v2.get_comments_temp(?,?)", [$data[$index]->idticket, $userid])[0]->comments);

            if (count($comments) >= 1 && !empty($comments[0]->id_comment)) {
                $data[$index]->comments = $comments;
            } else {
                $data[$index]->comments = [];
            }
            if (!isset($data[$index]->likes)) {
                $data[$index]->likes = [];
            }
            $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
            if (!isset($data[$index]->report_ticketlist)) {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
            $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataCeritakuTotal($request)
    {

        try {
            $data = [];
            $data = $this->connection
                ->table('public_v2.CeritaKita')
                ->select(DB::raw('count(*) as ttldataceritaku'))
                ->where('tag', '=', 'ceritaku')
                ->get()
                ->all();

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0]->ttldataceritaku;
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

    public function inputDataCeritaku($request)
    {
        $activity = $this->connection
            ->table('public_v2.UserStatus')
            ->select('platforms')
            ->where('nik', '=', $request->nik)
            ->orderBy('log', 'desc')
            ->take(1)
            ->get()
            ->all();

        $nik = $request->nik;
        $caption = $request->caption;
        $deskripsi = $request->deskripsi;
        $alias = base64_encode(microtime() . $request->nik);
        $gambar = isset($request->gambar) ? $request->gambar : '';
        $tag = 'ceritaku'; //$request->tag;
        $platform = $activity[0]->platforms;

        try {
            $idimg = substr($alias, 3, 8);

            if (!empty($gambar)) {
                $imgformat = array("jpeg", "jpg", "png");

                if ($gambar->getSize() > 1048576 || !in_array($gambar->extension(), $imgformat)) {
                    return [
                        'status' => 'File Error',
                        'data' => $this->data,
                        'message' => 'Format File dan Size tidak sesuai',
                        'code' => 200
                    ];
                } else {

                    $imgextension = $gambar->extension();

                    $data = $this->connection->insert("CALL public_v2.inputceritakita(?,?,?,?,?,?,?)", [$nik, $caption, $deskripsi, $alias, $idimg . '.' . $imgextension, $tag, $platform]);

                    if ($data) {
                        $imgpath = $this->path . '/Ceritakita/Ceritaku/' . $idimg . '.' . $imgextension;

                        $this->status = 'Success';
                        $this->message = 'Data has been process';
                        $this->data = $imgpath;
                    } else {
                        $this->status;
                        $this->message;
                        $this->data;
                    }
                }
            } else {
                $data = $this->connection->insert("CALL public_v2.inputceritakita(?,?,?,?,?,?,?)", [$nik, $caption, $deskripsi, $alias, $idimg . '.', $tag, $platform]);

                if ($data) {
                    $imgpath = $this->path . '/Ceritakita/Ceritaku/' . $idimg . '.';

                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = $imgpath;
                }

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
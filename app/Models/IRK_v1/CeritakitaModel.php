<?php

namespace App\Models\IRK_v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class CeritakitaModel extends Model
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

    public function showDataCeritakita($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];

        try {
            $data = $this->connection->select("select * from public.showceritakitalist(?,?)", [$userid, $page]);

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
            $data[$index]->comments = $this->connection->select("select * from public.showcomment(?,?)", [$data[$index]->idticket, $userid]);
            for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
                $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public.showreportcomment(?,?)", [$data[$index]->comments[$comment]->id_comment, $userid]);
                $data[$index]->report_comment = count($data[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
            }
            $data[$index]->likes = $this->connection->select("select * from public.showlike(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticketlist = $this->connection->select("select * from public.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataCeritakitaTotal($request)
    {

        try {
            $data = $this->connection
                ->table('CeritaKita')
                ->select(DB::raw('count(*) as ttldataceritakita'))
                ->get()
                ->all();

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0]->ttldataceritakita;
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

    public function showDataCeritakitaSub($request)
    {

        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];
        $tag = $request['tag'];
        $periode1 = $request['periode1'];
        $periode2 = $request['periode2'];
        $report = $request['report'];
        $content = $request['content'];

        try {
            $data = $this->connection->select("select * from public.showceritakitadetail(?,?,?,?,?,?,?)", [$userid, $page, $tag, $periode1, $periode2, $report, $content]);

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
            $data[$index]->comments = $this->connection->select("select * from public.showcomment(?,?)", [$data[$index]->idticket, $userid]);
            for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
                $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public.showreportcomment(?,?)", [$data[$index]->comments[$comment]->id_comment, $userid]);
                $data[$index]->report_comment = count($data[$index]->comments[$comment]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
            }
            $data[$index]->likes = $this->connection->select("select * from public.showlike(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticketlist = $this->connection->select("select * from public.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
            $data[$index]->report_ticket = count($data[$index]->report_ticketlist) > 0 ? 'Ya' : 'Tidak';
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function editDataContentCeritakita($request)
    {

        $nik = $request['nik'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try {
            $data = $this->connection->insert("CALL public.editceritakita(?,?,?)", [$nik, $idticket, $tag]);

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
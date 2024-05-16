<?php

namespace App\Models\IRK_v3;

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
            $data = [];
            $data = $this->connection->select("select * from public_v3.showceritakitalist(?,?)", [$userid, $page]);

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

            $comments = json_decode($this->connection->select("select * from public_v3.get_comments_temp(?,?)", [$data[$index]->idticket, $userid])[0]->comments);

            if (count($comments) >= 1 && !empty($comments[0]->id_comment)) {
                $data[$index]->comments = $comments;
            } else {
                $data[$index]->comments = [];
            }
            if (!isset($data[$index]->likes)) {
                $data[$index]->likes = [];
            }
            $data[$index]->likes = $this->connection->select("select * from public_v3.showlike(?,?)", [$data[$index]->idticket, $userid]);
            if (!isset($data[$index]->report_ticketlist)) {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
            $data[$index]->report_ticketlist = $this->connection->select("select * from public_v3.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
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
            $data = [];
            $data = $this->connection
                ->table('public_v3.CeritaKita')
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
        $follow = $request['follow'];
        $userstatus = $request['userstatus'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v3.showceritakitadetail(?,?,?,?,?,?,?,?,?) ORDER BY is_bookmark DESC", [$userid, $page, $tag, $periode1, $periode2, $report, $content, $follow, $userstatus]);


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
            $comments = json_decode($this->connection->select("select * from public_v3.get_comments_temp(?,?)", [$data[$index]->idticket, $userid])[0]->comments);

            if (count($comments) >= 1 && !empty($comments[0]->id_comment)) {
                $data[$index]->comments = $comments;
            } else {
                $data[$index]->comments = [];
            }
            if (!isset($data[$index]->likes)) {
                $data[$index]->likes = [];
            }
            $data[$index]->likes = $this->connection->select("select * from public_v3.showlike(?,?)", [$data[$index]->idticket, $userid]);
            if (!isset($data[$index]->report_ticketlist)) {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
            $data[$index]->report_ticketlist = $this->connection->select("select * from public_v3.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
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
        $reason = $request['reason'];

        try {
            $data = $this->connection->insert("CALL public_v3.editceritakita(?,?,?,?)", [$nik, $idticket, $tag, $reason]);

            if ($data) {

                $target = $this->connection
                    ->table('public_v3.CeritaKita')
                    ->select('employee', 'tag', 'is_used')
                    ->where('id_ticket', '=', $idticket)
                    ->get()[0];
                
                $likedby = $this->connection->select("select * from public_v3.getliked(?,?)", [$request['userid'], $idticket])[0]->getliked;
                $ttllike = $this->connection->select("select * from public_v3.getttllike(?)", [$idticket])[0]->getttllike;
                $ttlcomment = $this->connection->select("select * from public_v3.getttlcomment(?)", [$idticket])[0]->getttlcomment;
                $ttlnewcomment = $this->connection->select("select * from public_v3.getttlnewcomment(?)", [$idticket])[0]->getttlnewcomment;

                $this->status = 'Success';
                $this->message = 'Data has been process';
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

    public function editDataResponseAdmin($request)
    {

        $nik = $request['nik'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];
        $followup = $request['followup'];

        try {
            $data = $this->connection->insert("CALL public_v3.editresponseadmin(?,?,?,?)", [$nik, $idticket, $tag, $followup]);

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

    public function inputDataCeritakita($request)
    {
        $activity = $this->connection
            ->table('public_v3.UserStatus')
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
        $tag = $request->tag;
        $platform = $activity[0]->platforms;

        try {
            $idimg = substr($alias, 3, 8);

            if (!empty($gambar)) {
                $imgformat = array("jpeg", "jpg", "png");

                foreach ($gambar as $key => $value) {
                    if (!in_array($value->extension(), $imgformat) || $value->getSize() > 1048576) { // in bytes
                        return [
                            'status' => 'File Error',
                            'data' => $this->data,
                            'message' => 'Format File dan Size tidak sesuai',
                            'code' => 200
                        ];
                    } else {

                        $imgname[] = $idimg . '_' . $key . '.' . $value->extension();

                        $imgpath[] = $this->path . '/Ceritakita/' . ucfirst($tag) . '/' . $imgname[$key];
                    }
                }
                $images = '{' . implode(',', $imgname) . '}';

                $data = $this->connection->insert("CALL public_v3.input_CeritakitaNotif(?,?,?,?,?,?,?)", [$nik, $caption, $deskripsi, $alias, $images, $tag, $platform]);

                if ($data) {
                    $this->status = 'Success';
                    $this->message = 'Data has been process';
                    $this->data = $imgpath;
                } else {
                    $this->status;
                    $this->message;
                    $this->data;
                }

            } else {
                $images = '{' . $idimg . '.' . '}';

                $data = $this->connection->insert("CALL public_v3.input_CeritakitaNotif(?,?,?,?,?,?,?)", [$nik, $caption, $deskripsi, $alias, $images, $tag, $platform]);

                if ($data) {
                    $imgpath = $this->path . '/Ceritakita/' . ucfirst($tag) . '/' . $idimg . '.';

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

    public function showRekomendasiCeritakita($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? intval($request['page']) : 0;
        $userid = $request['userid'];

        try {
            $data = $this->helper->RecommenderSystem($userid, $page);

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
            if (count($this->connection->select("select * from public_v3.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid])) > 0) {
                $data[$index]->comments = $this->connection->select("select * from public_v3.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid]);
                $data[$index]->replycommentcount = count($data[$index]->comments);
                for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
                    if (count($this->connection->select("select * from public_v3.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->has_parent, $userid])) > 0) {
                        $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public_v3.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->parent_comment, $userid]);
                        $data[$index]->comments[$comment]->report_comment = 'Ya';
                    } else {
                        $data[$index]->comments[$comment]->report_commentlist = [];
                        $data[$index]->comments[$comment]->report_comment = 'Tidak';
                    }
                }
            } else {
                $data[$index]->comments = [];
                $data[$index]->replycommentcount = 0;
            }
            if (count($this->connection->select("select * from public_v3.showlike(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->likes = $this->connection->select("select * from public_v3.showlike(?,?)", [$data[$index]->idticket, $userid]);
            } else {
                $data[$index]->likes = [];
            }
            if (count($this->connection->select("select * from public_v3.showreportticket(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->report_ticketlist = $this->connection->select("select * from public_v3.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
                $data[$index]->report_ticket = 'Ya';
            } else {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

}
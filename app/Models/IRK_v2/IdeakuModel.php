<?php

namespace App\Models\IRK_v2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class IdeakuModel extends Model
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

    public function showDataIdeaku($request)
    {
        $page = isset($request['page']) && !empty($request['page']) ? $request['page'] : 0;
        $userid = $request['userid'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showideakulist(?,?)", [$userid, $page]);

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
            if (count($this->connection->select("select * from public_v2.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid])) > 0) {
                $data[$index]->comments = $this->connection->select("select * from public_v2.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid]);
                $data[$index]->replycommentcount = count($data[$index]->comments);
                for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
                    if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->has_parent, $userid])) > 0) {
                        $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->parent_comment, $userid]);
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
            if (count($this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
            } else {
                $data[$index]->likes = [];
            }
            if (count($this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
                $data[$index]->report_ticket = 'Ya';
            } else {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
        }

        // for ($index = 0; $index < count($data); $index++) {
        //     if (count($this->connection->select("select * from public_v2.showcomment(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->comments = $this->connection->select("select * from public_v2.showcomment(?,?)", [$data[$index]->idticket, $userid]);
        //         $data[$index]->replycommentcount = count($data[$index]->comments);
        //         for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
        //             $data[$index]->comments[$comment]->tipe_comment = 0;
        //             // if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->id_comment) . '_', ($data[$index]->comments[$comment]->parent_comment) . '_', $userid])) > 0) {
        //             //     $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->id_comment) . '_', ($data[$index]->comments[$comment]->parent_comment) . '_', $userid]);
        //             //     $data[$index]->comments[$comment]->report_comment = 'Ya';
        //             // } else {
        //             //     $data[$index]->comments[$comment]->report_commentlist = [];
        //             //     $data[$index]->comments[$comment]->report_comment = 'Tidak';
        //             // }
        //             if (count($this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, ($data[$index]->comments[$comment]->parent_comment) . '_', $userid])) > 0) {
        //                 $data[$index]->comments[$comment]->child_comments = $this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, ($data[$index]->comments[$comment]->parent_comment) . '_', $userid]);
        //                 $data[$index]->comments[$comment]->replycommentcount = count($data[$index]->comments[$comment]->child_comments);
        //                 for ($child_comment = 0; $child_comment < count($data[$index]->comments[$comment]->child_comments); $child_comment++) {
        //                     $data[$index]->comments[$comment]->child_comments[$child_comment]->tipe_comment = 1;
        //                     if (count($this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment, ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment + 1) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), $userid])) > 0) {
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments = $this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment, ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment + 1) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), $userid]);
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->replycommentcount = count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments);
        //                         for ($child_comment_closed = 0; $child_comment_closed < count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments); $child_comment_closed++) {
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->tipe_comment = 2;
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->child_comments = [];
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->replycommentcount = 0;
        //                             //$data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->id_parent_comment), $userid]);
        //                             //$data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_comment = count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
        //                         }
        //                     } else {
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments = [];
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->replycommentcount = 0;
        //                     }
        //                     // if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->id_parent_comment), $userid])) > 0) {
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->id_parent_comment), $userid]);
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_comment = 'Ya';
        //                     // } else {
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_commentlist = [];
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_comment = 'Tidak';
        //                     // }
        //                 }
        //             } else {
        //                 $data[$index]->comments[$comment]->child_comments = [];
        //                 $data[$index]->comments[$comment]->replycommentcount = 0;
        //             }
        //         }
        //     } else {
        //         $data[$index]->comments = [];
        //         $data[$index]->replycommentcount = 0;
        //     }
        //     if (count($this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
        //     } else {
        //         $data[$index]->likes = [];
        //     }
        //     if (count($this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
        //         $data[$index]->report_ticket = 'Ya';
        //     } else {
        //         $data[$index]->report_ticketlist = [];
        //         $data[$index]->report_ticket = 'Tidak';
        //     }

        // }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataIdeakuSingle($request)
    {
        $userid = $request['userid'];
        $idticket = $request['idticket'];

        try {
            $data = [];
            $data = $this->connection->select("select * from public_v2.showideakudetail(?,?)", [$userid, $idticket]);

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
            if (count($this->connection->select("select * from public_v2.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid])) > 0) {
                $data[$index]->comments = $this->connection->select("select * from public_v2.shownewcomment(?,?,?)", [$data[$index]->idticket, null, $userid]);
                $data[$index]->replycommentcount = count($data[$index]->comments);
                for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
                    if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->has_parent, $userid])) > 0) {
                        $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, $data[$index]->comments[$comment]->parent_comment, $userid]);
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
            if (count($this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
            } else {
                $data[$index]->likes = [];
            }
            if (count($this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid])) > 0) {
                $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
                $data[$index]->report_ticket = 'Ya';
            } else {
                $data[$index]->report_ticketlist = [];
                $data[$index]->report_ticket = 'Tidak';
            }
        }

        // for ($index = 0; $index < count($data); $index++) {
        //     if (count($this->connection->select("select * from public_v2.showcomment(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->comments = $this->connection->select("select * from public_v2.showcomment(?,?)", [$data[$index]->idticket, $userid]);
        //         $data[$index]->replycommentcount = count($data[$index]->comments);
        //         for ($comment = 0; $comment < count($data[$index]->comments); $comment++) {
        //             $data[$index]->comments[$comment]->tipe_comment = 0;
        //             // if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->id_comment) . '_', ($data[$index]->comments[$comment]->parent_comment) . '_', $userid])) > 0) {
        //             //     $data[$index]->comments[$comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->id_comment) . '_', ($data[$index]->comments[$comment]->parent_comment) . '_', $userid]);
        //             //     $data[$index]->comments[$comment]->report_comment = 'Ya';
        //             // } else {
        //             //     $data[$index]->comments[$comment]->report_commentlist = [];
        //             //     $data[$index]->comments[$comment]->report_comment = 'Tidak';
        //             // }
        //             if (count($this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, ($data[$index]->comments[$comment]->parent_comment) . '_', $userid])) > 0) {
        //                 $data[$index]->comments[$comment]->child_comments = $this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->id_comment, ($data[$index]->comments[$comment]->parent_comment) . '_', $userid]);
        //                 $data[$index]->comments[$comment]->replycommentcount = count($data[$index]->comments[$comment]->child_comments);
        //                 for ($child_comment = 0; $child_comment < count($data[$index]->comments[$comment]->child_comments); $child_comment++) {
        //                     $data[$index]->comments[$comment]->child_comments[$child_comment]->tipe_comment = 1;
        //                     if (count($this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment, ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment + 1) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), $userid])) > 0) {
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments = $this->connection->select("select * from public_v2.showreplycomment(?,?,?)", [$data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment, ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment + 1) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), $userid]);
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->replycommentcount = count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments);
        //                         for ($child_comment_closed = 0; $child_comment_closed < count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments); $child_comment_closed++) {
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->tipe_comment = 2;
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->child_comments = [];
        //                             $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->replycommentcount = 0;
        //                             //$data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->id_parent_comment), $userid]);
        //                             //$data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_comment = count($data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments[$child_comment_closed]->report_commentlist) > 0 ? 'Ya' : 'Tidak';
        //                         }
        //                     } else {
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->child_comments = [];
        //                         $data[$index]->comments[$comment]->child_comments[$child_comment]->replycommentcount = 0;
        //                     }
        //                     // if (count($this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->id_parent_comment), $userid])) > 0) {
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_commentlist = $this->connection->select("select * from public_v2.showreportcomment(?,?,?)", [($data[$index]->comments[$comment]->child_comments[$child_comment]->id_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->reply_to_id), ($data[$index]->comments[$comment]->child_comments[$child_comment]->parent_comment) . '_' . ($data[$index]->comments[$comment]->child_comments[$child_comment]->id_parent_comment), $userid]);
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_comment = 'Ya';
        //                     // } else {
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_commentlist = [];
        //                     //     $data[$index]->comments[$comment]->child_comments[$child_comment]->report_comment = 'Tidak';
        //                     // }
        //                 }
        //             } else {
        //                 $data[$index]->comments[$comment]->child_comments = [];
        //                 $data[$index]->comments[$comment]->replycommentcount = 0;
        //             }
        //         }
        //     } else {
        //         $data[$index]->comments = [];
        //         $data[$index]->replycommentcount = 0;
        //     }
        //     if (count($this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->likes = $this->connection->select("select * from public_v2.showlike(?,?)", [$data[$index]->idticket, $userid]);
        //     } else {
        //         $data[$index]->likes = [];
        //     }
        //     if (count($this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid])) > 0) {
        //         $data[$index]->report_ticketlist = $this->connection->select("select * from public_v2.showreportticket(?,?)", [$data[$index]->idticket, $userid]);
        //         $data[$index]->report_ticket = 'Ya';
        //     } else {
        //         $data[$index]->report_ticketlist = [];
        //         $data[$index]->report_ticket = 'Tidak';
        //     }

        // }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }

    public function showDataIdeakuTotal($request)
    {

        try {
            $data = [];
            $data = $this->connection
                ->table('public_v2.CeritaKita')
                ->select(DB::raw('count(*) as ttldataideaku'))
                ->where('tag', '=', 'ideaku')
                ->get()
                ->all();

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0]->ttldataideaku;
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

    public function inputDataIdeaku($request)
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
        $tag = 'ideaku'; //$request->tag;
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
                        $imgpath = $this->path . '/Ceritakita/Ideaku/' . $idimg . '.' . $imgextension;

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
                    $imgpath = $this->path . '/Ceritakita/Ideaku/' . $idimg . '.';

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
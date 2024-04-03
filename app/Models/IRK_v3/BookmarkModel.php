<?php

namespace App\Models\IRK_v3;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class BookmarkModel extends Model
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

    public function showDataBookmark($request)
    {
        try {
            $nik = $request['data']['nik'] ?? null;
            //$query = $this->connection->table('public_v2.Bookmark');
            $query = $this->connection->table('public_v3.Bookmark');
            $data = $query->where('nik', $nik)->get();

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

    public function inputDataBookmark($request)
    {

        $nik = $request['data']['nik'];
        $post_id = $request['data']['post_id'];

        try {
            //$data = $this->connection->insert("CALL public_v2.input_Bookmark(?, ?)", [$nik, $post_id]);
            $data = $this->connection->insert("CALL public_v3.input_Bookmark(?, ?)", [$nik, $post_id]);

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


    public function editDataBookmark($request)
    {
        $nik = $request['data']['nik'];
        $post_id = $request['data']['post_id'];

        try {
            //$data = $this->connection->insert('CALL public_v2.edit_Bookmark(?, ?)', [$nik, $post_id]);
            $data = $this->connection->insert('CALL public_v3.edit_Bookmark(?, ?)', [$nik, $post_id]);

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
}

<?php

namespace App\Models\IRK_v2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helper\IRKHelper;

class FaqModel extends Model
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

    public function showDataFaq($request)
    {

        $idfaq = $request['idfaq'];

        try {
            $data = [];
            $data = $this->connection
                ->table('public_v2.Faq')
                ->where('id_faq', '=', $idfaq)
                ->get()
                ->all();

            if ($data) {
                $this->status = 'Success';
                $this->message = 'Data has been process';
                $this->data = $data[0];
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

    public function inputDataFaq($request)
    {
        $question = $request['question'];
        $answer = $request['answer'];
        $category = $request['category'];
        $nik = $request['nik'];

        try {
            $data = $this->connection->insert("CALL public_v2.inputfaq(?,?,?,?)", [$question, $answer, $category, $nik]);

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

    public function editDataFaq($request)
    {
        $idfaq = $request['idfaq'];
        $question = $request['question'];
        $answer = $request['answer'];
        $category = $request['category'];
        $nik = $request['nik'];

        try {
            $data = $this->connection->insert("CALL public_v2.editfaq(?,?,?,?,?)", [$idfaq, $question, $answer, $category, $nik]);

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
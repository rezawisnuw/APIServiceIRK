<?php

namespace App\Models\IRK;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Cookie;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Helper\IRKHelper;

class ReportModel extends Model
{
    
	private $status = 'Failed';
    private $message = 'Data is cannot be process';
    private $data = [];

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

    public function showDataReportTicket($request)
    {
        try
        {
            $data = $this->connection
            ->table('ReportDetails')
            ->get();

            if($data) {
                $this->$status = 'Success';
                $this->$message = 'Data has been process';
                $this->$data = $data;
            } else{
                $this->$status;
                $this->$message;
                $this->$data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->$status,
            'data' => $this->$data,
            'message' => $this->$message
        ];
    }

    public function showDataReportComment($request)
    {
        try
        {
            $data = $this->connection
            ->table('ReportCommentDetails')
            ->get();

            if($data) {
                $this->$status = 'Success';
                $this->$message = 'Data has been process';
                $this->$data = $data;
            } else{
                $this->$status;
                $this->$message;
                $this->$data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->$status,
            'data' => $this->$data,
            'message' => $this->$message
        ];
    }

    public function inputDataReportTicket($request)
    {
        $nik = $request['nik'];
        $report = $request['report'];
        $idticket = $request['idticket'];
        $tag = $request['tag'];

        try
        {
            $data = $this->connection->insert("CALL inputreportticket(?,?,?,?)", [$nik,$report,$idticket,$tag]);

            if($data) {
                $this->$status = 'Success';
                $this->$message = 'Data has been process';
                $this->$data = $data;
            } else{
                $this->$status;
                $this->$message;
                $this->$data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->$status,
            'data' => $this->$data,
            'message' => $this->$message
        ];
    }

    public function inputDataReportComment($request)
    {
        $nik = $request['nik'];
        $report = $request['report'];
        $idcomment = $request['idcomment'];
        $tag = $request['tag'];

        try
        {
            $data = $this->connection->insert("CALL inputreportcomment(?,?,?,?)", [$nik,$report,$idcomment,$tag]);

            if($data) {
                $this->$status = 'Success';
                $this->$message = 'Data has been process';
                $this->$data = $data;
            } else{
                $this->$status;
                $this->$message;
                $this->$data;
            }

        }
        catch(\Throwable $e){ 
            $this->status = 'Error';
            $this->data = null;
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = '.$e->getMessage() : 'Error Database = '.$e->getMessage();
        }

        return [
            'status'  => $this->$status,
            'data' => $this->$data,
            'message' => $this->$message
        ];
    }

}
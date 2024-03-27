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
        try {
            $idfaq = $request['data']['idfaq'] ?? null;
            $question = $request['data']['question'] ?? null;
            $category = $request['data']['category'] ?? null;

            $query = $this->connection->table('public_v2.Faq');

            if (!is_null($idfaq) && is_null($question) && is_null($category)) {
                $data = $query->where('id_faq', $idfaq)->get()->toArray();
            } elseif (is_null($idfaq) && !is_null($question) && is_null($category)) {
                $data = $query->where('question', 'LIKE', "%$question%")->get()->toArray();
            } elseif (is_null($idfaq) && is_null($question) && !is_null($category)) {
                $category = strtolower($category);
                $data = $query->whereRaw('LOWER(category) = ?', $category)->get()->toArray();
            } elseif (is_null($idfaq) && is_null($question) && is_null($category)) {
                $data = $query->orderBy('category')->orderBy('id_faq')->get()->toArray();
            } else {
                $this->status = 'Error';
                $this->message = 'Data not found or payload is invalid';
                $this->data = null;
            }

            if (is_array($data)) {
                $this->status = 'Success';
                $this->message = 'Data has been processed';
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

    public function inputDataFaq($request)
    {
        $activity = $this->connection
            ->table('public_v2.UserStatus')
            ->select('platforms')
            ->where('nik', '=', $request['data']['nik'])
            ->orderBy('log', 'desc')
            ->take(1)
            ->get();

        $platform = $activity[0]->platforms;

        $param['list_sp'] = array(
            [
                'conn' => 'POR_DUMMY',
                'payload' => ['nik' => $request['data']['nik']],
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

        $question = $request['data']['question'];
        $answer = $request['data']['answer'];
        $category = $request['data']['category'];
        $alias = str_contains($level, 'Admin') && $platform == 'Website' ? $level : base64_encode(microtime() . $request['data']['nik']);
        $nik = $request['data']['nik'];

        try {
            $data = $this->connection->insert("CALL public_v2.inputfaq(?,?,?,?,?,?)", [$question, $answer, $category, $nik, $alias, $platform]);

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
        $idfaq = $request['data']['idfaq'];
        $question = $request['data']['question'];
        $answer = $request['data']['answer'];
        $category = $request['data']['category'];
        $nik = $request['data']['nik'];

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

    public function deleteDataFaq($request)
    {
        try {
            $idfaq = $request['data']['idfaq'] ?? null;

            $query = $this->connection->table('public_v2.Faq');

            if (!is_null($idfaq)) {
                $idfaqArray = explode(',', $idfaq);
                $data = $this->connection->table('public_v2.Faq')->whereIn('id_faq', $idfaqArray)->delete();
            }
            if ($data) {
                $this->status = 'Success';
                $this->message = 'Data has been deleted';
            } else {
                $this->status = 'Error';
                $this->message = 'Failed to delete data';
            }
        } catch (\Throwable $e) {
            $this->status = 'Error';
            $this->message = $e->getCode() == 0 ? 'Error Function Laravel = ' . $e->getMessage() : 'Error Database = ' . $e->getMessage();
        }

        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }
}
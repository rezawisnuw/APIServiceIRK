<?php

namespace App\Http\Controllers\IRK_v1;

use Illuminate\Http\Request;
use App\Models\IRK_v1\CurhatkuModel;
use App\Helper\IRKHelper;

class CurhatkuController extends Controller
{
    private $status = 'Failed', $data = [], $message = 'Process is not found', $model, $helper;

    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();

        $slug = $request->route('slug');
        $x = $request->route('x');
        $this->base = 'v' . $x . '/' . $slug;

        $model = new CurhatkuModel($request, $slug);
        $this->model = $model;

        $helper = new IRKHelper($request);
        $this->helper = $helper;

    }

    public function get(Request $request)
    {

        $formbody = $request->data;
        $codekey = null;

        try {

            switch ($codekey = $formbody['code']) {
                case 1:
                    $result = $this->model->showDataCurhatku($formbody);
                    break;
                case 2:
                    $result = $this->model->showDataCurhatkuSingle($formbody);
                    break;
                case 3:
                    $result = $this->model->showDataCurhatkuTotal($formbody);
                    break;
                default:
                    $result = collect([
                        'status' => $this->status,
                        'data' => $codekey,
                        'message' => $this->message
                    ]);
            }

        } catch (\Throwable $e) {
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message' => $e->getCode() == 0 ? 'Error Controller Laravel = ' . $e->getMessage() : 'Error Model Laravel = ' . $e->getMessage() . ' On Switch Case = ' . $codekey
            ]);
        }

        return response()->json($result);
    }

    public function post(Request $request)
    {
        $codekey = null;

        $datadecode = json_decode($request->data);

        if (isset($request->file)) {
            $filedecode = json_decode($request->file);
            $b64filedecode = base64_decode($filedecode);

            $arrayfile = $this->helper->BlobtoFile($b64filedecode);
            isset($datadecode->gambar) && !empty($datadecode->gambar) ? $datadecode->gambar = $arrayfile[0] : $datadecode->gambar = '';
        }

        $formbody = $datadecode;

        try {

            switch ($codekey = $formbody->code) {
                case 1:
                    $result = $this->model->inputDataCurhatku($formbody);
                    break;
                default:
                    $result = collect([
                        'status' => $this->status,
                        'data' => $codekey,
                        'message' => $this->message
                    ]);
            }

        } catch (\Throwable $e) {
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message' => $e->getCode() == 0 ? 'Error Controller Laravel = ' . $e->getMessage() : 'Error Model Laravel = ' . $e->getMessage() . ' Switch Case = ' . $codekey
            ]);
        }

        return response()->json($result);
    }

    public function put(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
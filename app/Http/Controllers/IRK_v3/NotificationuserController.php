<?php

namespace App\Http\Controllers\IRK_v3;

use Illuminate\Http\Request;
use App\Models\IRK_v3\NotificationuserModel;
use App\Helper\IRKHelper;

class NotificationuserController extends Controller
{
    private $status = 'Failed', $data = [], $message = 'Process is not found', $model, $helper;

    public function __construct(Request $request)
    {
        // Call the parent constructor
        //parent::__construct();

        $slug = $request->route('slug');
        $x = $request->route('x');
        $this->base = 'v' . $x . '/' . $slug;

        $model = new NotificationuserModel($request, $slug);
        $this->model = $model;

        $helper = new IRKHelper($request);
        $this->helper = $helper;
    }


    public function get(Request $request)
    {
        $data = $request->json()->all();
        
        try {
            $result = $this->model->showDataNotificationuser($data);
        } catch (\Throwable $e) {
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message' => $e->getCode() == 0 ? 'Error Controller Laravel = ' . $e->getMessage() : 'Error Model Laravel = ' . $e->getMessage()
            ]);
        }

        return response()->json($result);
    }


    public function post(Request $request)
    {
        $data = $request->json()->all();

        try {
            $result = $this->model->inputDataNotificationuser($data);
        } catch (\Throwable $e) {
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message' => $e->getCode() == 0 ? 'Error Controller Laravel = ' . $e->getMessage() : 'Error Model Laravel = ' . $e->getMessage()
            ]);
        }

        return response()->json($result);
    }


    public function put(Request $request)
    {
        $data = $request->json()->all();

        try {
            $result = $this->model->editDataNotificationuser($data);
        } catch (\Throwable $e) {
            $result = collect([
                'status' => 'Error',
                'data' => null,
                'message' => $e->getCode() == 0 ? 'Error Controller Laravel = ' . $e->getMessage() : 'Error Model Laravel = ' . $e->getMessage()
            ]);
        }

        return response()->json($result);
    }


    public function delete(Request $request)
    {

    }
}

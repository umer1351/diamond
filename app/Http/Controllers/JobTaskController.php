<?php

namespace App\Http\Controllers;

use App\Models\JobTask;
use App\Services\Concrete\AccountService;
use App\Services\Concrete\CommonService;
use App\Services\Concrete\JobTaskService;
use App\Services\Concrete\SupplierService;
use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class JobTaskController extends Controller
{
    use JsonResponse;
    protected $job_task_service;
    protected $account_service;
    protected $supplier_service;
    protected $other_product_service;
    protected $warehouse_service;
    protected $common_service;

    public function __construct(
        JobTaskService $job_task_service,
        SupplierService $supplier_service,
        AccountService $account_service,
        WarehouseService $warehouse_service,
        CommonService $common_service
    ) {
        $this->job_task_service = $job_task_service;
        $this->account_service = $account_service;
        $this->supplier_service = $supplier_service;
        $this->warehouse_service = $warehouse_service;
        $this->common_service = $common_service;
    }
    public function index()
    {
        
        abort_if(Gate::denies('job_task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $suppliers = $this->supplier_service->getAllActiveSupplier();
        return view('job_task.index', compact('suppliers'));
    }
    public function getData(Request $request)
    {
        
        abort_if(Gate::denies('job_task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $end = $request['end_date'] ?? date('Y-m-d ', strtotime(Carbon::now()));
            $start = $request['start_date'] ?? date('Y-m-d ', strtotime(Carbon::now()));
            $supplier_id = $request['supplier_id'] ?? Auth::user()->supplier_id;
            $obj = [
                "supplier_id" => $supplier_id,
                "end" => $end,
                "start" => $start
            ];
            return $this->job_task_service->getSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('job_task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'id'                => 'required',
                'job_task_date'     => 'required',
                'supplier_id'       => 'required',
                'warehouse_id'      => 'required',
                'delivery_date'      => 'required',
                'reference_no'      => 'required',
                'purchaseOrderDetail'   => 'required'
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            $validation_error = "";
            foreach ($validation->errors()->all() as $message) {
                $validation_error .= $message;
            }
            return $this->validationResponse(
                $validation_error
            );
        }

        try {
            $obj = $request->all();
            $job_task = $this->job_task_service->save($obj);
            return  $this->success(
                config("enum.saved"),
                $job_task
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }


    public function print($id)
    {
        abort_if(Gate::denies('job_task_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {

            $job_task =  $this->job_task_service->getById($id);
            $job_task_detail = $this->job_task_service->JobTaskDetail($id);


            return view('job_task/partials.print', compact('job_task', 'job_task_detail'));
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }


    public function destroy($id)
    {
        abort_if(Gate::denies('job_task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $job_task = $this->job_task_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                [],
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.noDelete'),);
        }
    }

}

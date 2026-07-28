<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\CommonService;
use App\Services\Concrete\CustomerService;
use App\Services\Concrete\GoldImpurityPurchaseService;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GoldImpurityPurchaseController extends Controller
{
    use JsonResponse;
    protected $gold_impurity_service;
    protected $account_service;
    protected $customer_service;
    protected $warehouse_service;
    protected $common_service;

    public function __construct(
        GoldImpurityPurchaseService $gold_impurity_service,
        CustomerService $customer_service,
        AccountService $account_service,
        CommonService $common_service
    ) {
        $this->gold_impurity_service = $gold_impurity_service;
        $this->account_service = $account_service;
        $this->customer_service = $customer_service;
        $this->common_service = $common_service;
    }
    public function index()
    {
        abort_if(Gate::denies('gold_impurity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = $this->customer_service->getAllActiveCustomer();
        $accounts = $this->account_service->getAllActiveChild();
        return view('purchases/gold_impurity.index', compact('customers', 'accounts'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('gold_impurity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $end = $request['end_date'] ?? date('Y-m-d ', strtotime(Carbon::now()));
            $start = $request['start_date'] ?? date('Y-m-d ', strtotime(Carbon::now()));
            $customer_id = $request['customer_id'] ?? '';
            $posted = $request['posted'] ?? '';
            $obj = [
                "customer_id" => $customer_id,
                "end" => $end,
                "start" => $start,
                "posted" => $posted,
            ];
            return $this->gold_impurity_service->getSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function create()
    {
        abort_if(Gate::denies('gold_impurity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $gold_impurity = $this->gold_impurity_service->saveGoldImpurity();
        return view('purchases/gold_impurity.create', compact(
            'gold_impurity'
        ));
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('gold_impurity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'id'             => 'required',
                'customer_id'    => 'required',
                'total_weight'          => 'required',
                'total_qty'          => 'required',
                'total'          => 'required',
                'goldImpurityDetail'  => 'required'
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
            $gold_impurity = $this->gold_impurity_service->save($obj);
            return  $this->success(
                config("enum.saved"),
                $gold_impurity
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }


    public function print($id)
    {
        abort_if(Gate::denies('gold_impurity_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // try {

            $gold_impurity =  $this->gold_impurity_service->getById($id);
            $gold_impurity_detail = $this->gold_impurity_service->GoldImpurityDetail($id);


            return view('purchases/gold_impurity/partials.print', compact('gold_impurity', 'gold_impurity_detail'));
        // } catch (Exception $e) {
        //     return $this->error(config('enum.error'));
        // }
    }


    public function unpost($id)
    {
        abort_if(Gate::denies('gold_impurity_unpost'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $this->gold_impurity_service->unpost($id);
            return $this->success(
                config('enum.unposted'),
                []
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function post(Request $request)
    {
        abort_if(Gate::denies('gold_impurity_post'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $gold_impurity = $this->gold_impurity_service->post($request->all());
            if ($gold_impurity != 'true') {
                return $this->error(
                    $gold_impurity
                );
            }
            return $this->success(
                config('enum.posted'),
                []
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('gold_impurity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $gold_impurity = $this->gold_impurity_service->deleteById($id);
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

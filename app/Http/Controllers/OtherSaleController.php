<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\CommonService;
use App\Services\Concrete\CustomerService;
use App\Services\Concrete\OtherProductService;
use App\Services\Concrete\OtherSaleService;
use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OtherSaleController extends Controller
{
    use JsonResponse;
    protected $other_sale_service;
    protected $account_service;
    protected $customer_service;
    protected $other_product_service;
    protected $warehouse_service;
    protected $common_service;

    public function __construct(
        OtherSaleService $other_sale_service,
        CustomerService $customer_service,
        AccountService $account_service,
        OtherProductService $other_product_service,
        WarehouseService $warehouse_service,
        CommonService $common_service
    ) {
        $this->other_sale_service = $other_sale_service;
        $this->account_service = $account_service;
        $this->customer_service = $customer_service;
        $this->other_product_service = $other_product_service;
        $this->warehouse_service = $warehouse_service;
        $this->common_service = $common_service;
    }
    public function index()
    {
        abort_if(Gate::denies('other_sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = $this->customer_service->getAllActiveCustomer();
        $accounts = $this->account_service->getAllActiveChild();
        return view('other_sale.index', compact('customers', 'accounts'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('other_sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            return $this->other_sale_service->getSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function create()
    {
        abort_if(Gate::denies('other_sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $other_sale = $this->other_sale_service->saveOtherSale();
        $other_products = $this->other_product_service->getAllActiveOtherProduct();
        $warehouses = $this->warehouse_service->getAll();
        return view('other_sale.create', compact(
            'other_sale',
            'other_products',
            'warehouses'
        ));
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('other_sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'id'             => 'required',
                'other_sale_date'      => 'required',
                'customer_id'    => 'required',
                'warehouse_id'    => 'required',
                'total'          => 'required',
                'otherProductDetail'  => 'required'
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
            $other_sale = $this->other_sale_service->save($obj);
            return  $this->success(
                config("enum.saved"),
                $other_sale
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }


    public function print($id)
    {
        abort_if(Gate::denies('other_sale_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {

            $other_sale =  $this->other_sale_service->getById($id);
            $other_sale_detail = $this->other_sale_service->otherSaleDetail($id);


            return view('other_sale/partials.print', compact('other_sale', 'other_sale_detail'));
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }


    public function unpost($id)
    {
        abort_if(Gate::denies('other_sale_unpost'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $this->other_sale_service->unpost($id);
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
        abort_if(Gate::denies('other_sale_post'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $other_sale = $this->other_sale_service->post($request->all());
            if ($other_sale != 'true') {
                return $this->error(
                    $other_sale
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
        abort_if(Gate::denies('other_sale_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $other_sale = $this->other_sale_service->deleteById($id);
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

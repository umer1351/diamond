<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\CommonService;
use App\Services\Concrete\CustomerService;
use App\Services\Concrete\FinishProductService;
use App\Services\Concrete\ProductService;
use App\Services\Concrete\SaleService;
use App\Services\Concrete\BeadTypeService;
use App\Services\Concrete\CompanySettingService;
use App\Services\Concrete\DiamondClarityService;
use App\Services\Concrete\DiamondColorService;
use App\Services\Concrete\DiamondCutService;
use App\Services\Concrete\DiamondTypeService;
use App\Services\Concrete\SaleOrderService;
use App\Services\Concrete\StoneCategoryService;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    use JsonResponse;
    protected $sale_service;
    protected $account_service;
    protected $customer_service;
    protected $product_service;
    protected $finish_product_service;
    protected $common_service;
    protected $bead_type_service;
    protected $stone_category_service;
    protected $diamond_type_service;
    protected $diamond_color_service;
    protected $diamond_cut_service;
    protected $diamond_clarity_service;
    protected $company_setting_service;

    public function __construct(
        SaleService $sale_service,
        CustomerService $customer_service,
        AccountService $account_service,
        ProductService $product_service,
        FinishProductService $finish_product_service,
        CommonService $common_service,
        BeadTypeService $bead_type_service,
        StoneCategoryService $stone_category_service,
        DiamondTypeService $diamond_type_service,
        DiamondColorService $diamond_color_service,
        DiamondCutService $diamond_cut_service,
        DiamondClarityService $diamond_clarity_service,
        CompanySettingService $company_setting_service
    ) {
        $this->sale_service = $sale_service;
        $this->account_service = $account_service;
        $this->customer_service = $customer_service;
        $this->product_service = $product_service;
        $this->finish_product_service = $finish_product_service;
        $this->common_service = $common_service;
        $this->bead_type_service = $bead_type_service;
        $this->stone_category_service = $stone_category_service;
        $this->diamond_type_service = $diamond_type_service;
        $this->diamond_color_service = $diamond_color_service;
        $this->diamond_cut_service = $diamond_cut_service;
        $this->diamond_clarity_service = $diamond_clarity_service;
        $this->company_setting_service = $company_setting_service;
    }
    public function index()
    {
        abort_if(Gate::denies('sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = $this->customer_service->getAllActiveCustomer();
        $accounts = $this->account_service->getAllActiveChild();
        $setting = $this->company_setting_service->getSetting();
        return view('sale.index', compact('customers', 'accounts','setting'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('sale_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            return $this->sale_service->getSaleSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function create()
    {
        abort_if(Gate::denies('sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $sale = $this->sale_service->saveSale();
        $finish_product = $this->finish_product_service->getAllActiveFinishProduct();
        $bead_types = $this->bead_type_service->getAllActive();
        $stone_categories = $this->stone_category_service->getAllActive();
        $diamond_types = $this->diamond_type_service->getAllActive();
        $diamond_colors = $this->diamond_color_service->getAllActive();
        $diamond_cuts = $this->diamond_cut_service->getAllActive();
        $diamond_clarities = $this->diamond_clarity_service->getAllActive();
        return view('sale.create', compact(
            'sale',
            'finish_product',
            'bead_types',
            'stone_categories',
            'diamond_types',
            'diamond_colors',
            'diamond_cuts',
            'diamond_clarities'
        ));
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'id'             => 'required',
                'sale_date'      => 'required',
                'customer_id'    => 'required',
                'sub_total'          => 'required',
                'total'          => 'required',
                'productDetail'  => 'required'
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
            $sale = $this->sale_service->save($obj);
            return  $this->success(
                config("enum.saved"),
                $sale
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function salePayment(Request $request){
        abort_if(Gate::denies('customer_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'sale_id'               => 'required',
                'customer_id'           => 'required',
                'total_received'        => 'required',
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
            $sale = $this->sale_service->salePayment($obj);
            return  $this->success(
                'Sale Payment Added!',
                $sale
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function print($id)
    {
        abort_if(Gate::denies('sale_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {

            $sale =  $this->sale_service->getById($id);
            $sale_detail = $this->sale_service->saleDetail($id);


            return view('sale/partials.print', compact('sale', 'sale_detail'));
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function getSaleDetailById($id)
    {
        abort_if(Gate::denies('sale_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {

            $sale =  $this->sale_service->getById($id);

            return $this->success(
                config('enum.success'),
                $sale,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function unpostSale($id)
    {
        abort_if(Gate::denies('sale_unpost'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $this->sale_service->unpostSale($id);
            return $this->success(
                config('enum.unposted'),
                []
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function postSale(Request $request)
    {
        abort_if(Gate::denies('sale_post'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $sale = $this->sale_service->postSale($request->all());
            if ($sale != 'true') {
                return $this->error(
                    $sale
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
        abort_if(Gate::denies('sale_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $sale = $this->sale_service->deleteSaleById($id);
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

<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\CustomerPaymentService;
use App\Services\Concrete\CustomerService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CustomerPaymentController extends Controller
{
    use JsonResponse;
    protected $customer_service;
    protected $customer_payment_service;
    protected $account_service;

    public function __construct(
        CustomerService  $customer_service,
        CustomerPaymentService $customer_payment_service,
        AccountService $account_service
    ) {
        $this->customer_service = $customer_service;
        $this->customer_payment_service = $customer_payment_service;
        $this->account_service = $account_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        abort_if(Gate::denies('customer_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $customers = $this->customer_service->getAllActiveAccountCustomer();
            $accounts = $this->account_service->getAllActiveChild();
            return view('customer_payment.index', compact('accounts', 'customers'));
        } catch (Exception $e) {
            return redirect('home');
        }
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('customer_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $obj = $request->all();
        return $this->customer_payment_service->getCustomerPaymentSource($obj);
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('customer_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        $this->validate($request, [
            'customer_id' => 'required',
            'currency' => 'required',
            'sub_total' => 'required',
            'account_id' => 'required',
            'payment_date' => 'required',
        ]);
        try {
            $obj = [
                'customer_id' => $request->customer_id,
                'currency' => $request->currency,
                'account_id' => $request->account_id ?? null,
                'payment_date' => $request->payment_date,
                'reference' => $request->reference,
                'sub_total' => $request->sub_total,
                'total' => $request->total,
                'tax' => $request->tax,
                'tax_amount' => $request->tax_amount,
                'tax_account_id' => $request->tax_account_id ?? null
            ];
            $customer_payment = $this->customer_payment_service->saveCustomerPayment($obj, $request->id);
            return $this->success(
                config('enum.saved'),
                $customer_payment
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function advance(Request $request)
    {
        abort_if(Gate::denies('customer_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        $this->validate($request, [
            'customer_id' => 'required',
            'sale_order_id' => 'required',
            'amount' => 'required',
            'recieving_account_id' => 'required'
        ]);
        try {
            $obj = [
                'customer_id' => $request->customer_id,
                'sale_order_id' => $request->sale_order_id,
                'currency' => 0,
                'account_id' => $request->recieving_account_id ?? null,
                'payment_date' => date('Y-m-d'),
                'reference' => $request->reference??'',
                'sub_total' => $request->amount??0,
                'total' => $request->amount??0,
                'tax' => 0,
                'tax_amount' => 0,
                'tax_account_id' => null
            ];
            $customer_payment = $this->customer_payment_service->saveCustomerPayment($obj, $request->id);
            return $this->success(
                config('enum.saved'),
                $customer_payment
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function edit($id)
    {
        abort_if(Gate::denies('customer_payment_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $customer_payment = $this->customer_payment_service->getCustomerPaymentById($id);
            return $this->success(
                config('enum.success'),
                $customer_payment,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('customer_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->customer_payment_service->deleteCustomerPaymentById($id);
            return $this->success(
                config('enum.delete'),
                $response,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.noDelete'),);
        }
    }
}

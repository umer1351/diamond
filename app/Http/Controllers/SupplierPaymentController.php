<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\SupplierPaymentService;
use App\Services\Concrete\SupplierService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SupplierPaymentController extends Controller
{
    use JsonResponse;
    protected $supplier_service;
    protected $supplier_payment_service;
    protected $account_service;

    public function __construct(
        SupplierService  $supplier_service,
        SupplierPaymentService $supplier_payment_service,
        AccountService $account_service
    ) {
        $this->supplier_service = $supplier_service;
        $this->supplier_payment_service = $supplier_payment_service;
        $this->account_service = $account_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        abort_if(Gate::denies('supplier_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $suppliers = $this->supplier_service->getAllActiveSupplier();
            $accounts = $this->account_service->getAllActiveChild();
            return view('supplier_payment.index', compact('accounts', 'suppliers'));
        } catch (Exception $e) {
            return redirect('home');
        }
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('supplier_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $obj = $request->all();
        return $this->supplier_payment_service->getSupplierPaymentSource($obj);
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('supplier_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        $this->validate($request, [
            'supplier_id' => 'required',
            'currency' => 'required',
            'sub_total' => 'required',
            'account_id' => 'required',
            'payment_date' => 'required',
        ]);
        try {
            $obj = [
                'supplier_id' => $request->supplier_id,
                'currency' => $request->currency,
                'account_id' => $request->account_id ?? null,
                'payment_date' => $request->payment_date,
                'cheque_ref' => $request->cheque_ref,
                'sub_total' => $request->sub_total,
                'total' => $request->total,
                'tax' => $request->tax,
                'tax_amount' => $request->tax_amount,
                'tax_account_id' => $request->tax_account_id ?? null
            ];
            $supplier_payment = $this->supplier_payment_service->saveSupplierPayment($obj, $request->id);
            return $this->success(
                config('enum.saved'),
                $supplier_payment
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('supplier_payment_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $supplier_payment = $this->supplier_payment_service->getSupplierPaymentById($id);
            return $this->success(
                config('enum.success'),
                $supplier_payment,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('supplier_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->supplier_payment_service->deleteSupplierPaymentById($id);
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

<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Services\Concrete\AccountService;
use App\Services\Concrete\SupplierService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    use JsonResponse;
    protected $supplier_service;
    protected $account_service;


    public function __construct(
        SupplierService $supplier_service,
        AccountService $account_service
    ) {
        $this->supplier_service = $supplier_service;
        $this->account_service = $account_service;
    }

    public function index()
    {
        abort_if(Gate::denies('suppliers_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('suppliers.index');
    }


    public function getData(Request $request)
    {
        abort_if(Gate::denies('suppliers_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->supplier_service->getSupplierSource();
    }

    public function create()
    {
        abort_if(Gate::denies('suppliers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = $this->account_service->getAllActiveChild();
        return view('suppliers.create',compact('accounts'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('suppliers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:191'],
                'contact' => ['required', 'string'],
                'type' => ['required'],
                'gold_waste' => ['required'],
                'stone_waste' => ['required'],
                'kaat' => ['required'],
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $obj = $request->all();
            $company = CompanySetting::find(1);
            if ($company->supplier_account_id != null) {
                $obj['account_id'] = $company->supplier_account_id;
            } else {
                return redirect()->back()->with('error', 'Please first update supplier/karigar account in setting!');
            }
            $supplier = $this->supplier_service->save($obj);

            if (!$supplier)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect('suppliers')->with('success', config('enum.saved'));
        } catch (Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('suppliers_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $supplier = $this->supplier_service->getById($id);
        $accounts = $this->account_service->getAllActiveChild();
        return view('suppliers.create', compact('supplier','accounts'));
    }


    public function status($id)
    {
        abort_if(Gate::denies('suppliers_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $supplier = $this->supplier_service->statusById($id);

            if ($supplier)
                return $this->success(
                    config('enum.status'),
                    []

                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('suppliers_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $supplier = $this->supplier_service->deleteById($id);

            if ($supplier)
                return $this->success(
                    config('enum.delete'),
                    []

                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getSupplierById($id)
    {
        try {
            return $this->success(
                config('enum.success'),
                $this->supplier_service->getById($id)

            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}

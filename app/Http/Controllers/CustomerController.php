<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Services\Concrete\AccountService;
use App\Services\Concrete\CustomerService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    use JsonResponse;
    protected $customer_service;
    protected $account_service;


    public function __construct(
        CustomerService $customer_service,
        AccountService $account_service
    ) {
        $this->customer_service = $customer_service;
        $this->account_service = $account_service;
    }

    public function index()
    {
        abort_if(Gate::denies('customers_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('customers.index');
    }


    public function getData(Request $request)
    {
        abort_if(Gate::denies('customers_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->customer_service->getCustomerSource();
    }
    public function allJson()
    {
        $customer = $this->customer_service->getAllActiveCustomer();
        return  $this->success(
            config("enum.success"),
            $customer,
            false
        );
    }
    public function create()
    {
        abort_if(Gate::denies('customers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = $this->account_service->getAllActiveChild();
        return view('customers.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('customers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'contact' => 'required|string|unique:customers,contact,' . $request->id,
                'cnic' => 'nullable|unique:customers,cnic,' . $request->id,
                'cnic_images.*' => 'mimes:jpg,jpeg,png,bmp|max:2048'
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $obj = $request->all();
            $company = CompanySetting::find(1);
            if ($company->customer_account_id != null) {
                $obj['account_id'] = $company->customer_account_id;
            } else {
                return redirect()->back()->with('error', 'Please first update customer account in setting!');
            }
            $imagePaths = [];

            if ($request->hasFile('cnic_images')) {
                foreach ($request->file('cnic_images') as $image) {
                    $fileName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('cnic_images'), $fileName);
                    $imagePaths[] = 'cnic_images/' . $fileName;
                }
                $obj['cnic_images'] = json_encode($imagePaths);
            }
            $customer = $this->customer_service->save($obj);

            if (!$customer)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect('customers')->with('success', config('enum.saved'));
        } catch (Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function storeJson(Request $request)
    {
        abort_if(Gate::denies('customers_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:191',
                'contact' => 'required|string|unique:customers,contact',
                'cnic' => 'nullable|unique:customers,cnic'
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
            $company = CompanySetting::find(1);
            if ($company->customer_account_id != null) {
                $obj['account_id'] = $company->customer_account_id;
            } else {
                return $this->validationResponse(
                    'Please first update customer account in setting!'
                );
            }
            $obj['id'] = '';
            $customer = $this->customer_service->save($obj);

            return  $this->success(
                config("enum.saved"),
                $customer
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('customers_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = $this->customer_service->getById($id);
        $accounts = $this->account_service->getAllActiveChild();
        return view('customers.create', compact('customer', 'accounts'));
    }

    public function detailJson($id)
    {
        $customer = $this->customer_service->getById($id);
        return  $this->success(
            config("enum.success"),
            $customer,
            false
        );
    }

    public function status($id)
    {
        abort_if(Gate::denies('customers_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $customer = $this->customer_service->statusById($id);

            if ($customer)
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
        abort_if(Gate::denies('customers_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $customer = $this->customer_service->deleteById($id);

            if ($customer)
                return $this->success(
                    config('enum.delete'),
                    []

                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}

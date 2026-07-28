<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\EmployeeService;
use Illuminate\Http\Request;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    use JsonResponse;
    protected $employee_service;
    protected $account_service;


    public function __construct(
        EmployeeService $employee_service,
        AccountService $account_service
    ) {
        $this->employee_service = $employee_service;
        $this->account_service = $account_service;
    }

    public function index()
    {
        abort_if(Gate::denies('employees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('employees.index');
    }


    public function getData(Request $request)
    {
        abort_if(Gate::denies('employees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->employee_service->getEmployeeSource();
    }

    public function create()
    {
        abort_if(Gate::denies('employees_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = $this->account_service->getAllActiveChild();
        return view('employees.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('employees_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            if ($request->id == null) {
                $validator = Validator::make($request->all(), [
                    'employee_id' => ['required', 'max:191'],
                    'name' => ['required', 'string', 'max:191'],
                    'cnic' => ['required', 'string'],
                    'contact' => ['required', 'string'],
                    'email' => ['required', 'email', 'string', 'max:191'],
                    'gender' => ['required'],
                    'date_of_birth' => ['required'],
                    'gender' => ['required'],
                    'emergency_name' => ['required', 'string', 'max:191'],
                    'emergency_contact' => ['required', 'string', 'max:191'],
                    'emergency_relation' => ['required', 'string', 'max:191'],
                    'job_role' => ['required', 'string', 'max:191'],
                    'department' => ['required'],
                    'employee_type' => ['required'],
                    'shift' => ['required'],
                    'date_of_joining' => ['required'],
                    'salary' => ['required'],
                    'payment_method' => ['required'],
                    'is_overtime' => ['required'],
                    'picture' => ['required', 'mimes:jpg,jpeg,png,bmp', 'max:2048']
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'picture' => ['mimes:jpg,jpeg,png,bmp', 'max:2048']
                ]);
            }

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $obj = $request->all();
            $imagePaths = [];

            if ($request->hasFile('picture')) {
                $image = $request->file('picture');
                $fileName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('picture'), $fileName);
                $obj['picture'] = 'picture/' . $fileName;
            }
            $employee = $this->employee_service->save($obj);

            if (!$employee)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect('employees')->with('success', config('enum.saved'));
        } catch (Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('employees_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $employee = $this->employee_service->getById($id);
        $accounts = $this->account_service->getAllActiveChild();
        return view('employees.create', compact('employee', 'accounts'));
    }


    public function status($id)
    {
        abort_if(Gate::denies('employees_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $employee = $this->employee_service->statusById($id);

            if ($employee)
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
        abort_if(Gate::denies('employees_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $employee = $this->employee_service->deleteById($id);

            if ($employee)
                return $this->success(
                    config('enum.delete'),
                    []

                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}

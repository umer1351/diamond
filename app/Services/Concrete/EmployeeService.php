<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class EmployeeService
{
    // initialize protected model variables
    protected $model_employee;

    public function __construct()
    {
        // set the model
        $this->model_employee = new Repository(new Employee());
    }

    public function getEmployeeSource()
    {
        $model = $this->model_employee->getModel()::with('account_name')->where('is_deleted', 0);

        $data = DataTables::of($model)
            ->addColumn('account', function ($item) {
                $name = $item->account_name->name ?? '';
                $code = $item->account_name->code ?? '';
                return $code . ' ' . $name;
            })
            ->addColumn('status', function ($item) {
                if ($item->is_active == 1) {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                } else {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                }
                return $status;
            })

            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' href='employees/edit/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                // $view_column    = "<a class='text-warning mr-2' href='employees/view/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteEmployee' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('employees_edit'))
                    $action_column .= $edit_column;


                if (Auth::user()->can('employees_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['account', 'status', 'action'])
            ->make(true);
        return $data;
    }

    public function getAllActiveEmployee()
    {
        return $this->model_employee->getModel()::with('account_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->get();
    }

    public function save($obj)
    {
        if ($obj['id'] != null && $obj['id'] != '') {
            $obj['updatedby_id'] = Auth::user()->id;
            $this->model_employee->update($obj, $obj['id']);
            $saved_obj = $this->model_employee->find($obj['id']);
        } else {
            $obj['createdby_id'] = Auth::user()->id;
            $saved_obj = $this->model_employee->create($obj);
        }

        if (!$saved_obj)
            return false;

        return true;
    }

    public function getById($id)
    {
        return $this->model_employee->getModel()::find($id);
    }

    public function statusById($id)
    {
        $employee = $this->model_employee->getModel()::find($id);
        if ($employee->is_active == 0) {
            $employee->is_active = 1;
        } else {
            $employee->is_active = 0;
        }
        $employee->updatedby_id = Auth::user()->id;
        $employee->update();

        if ($employee)
            return true;

        return false;
    }

    public function deleteById($id)
    {
        $employee = $this->model_employee->getModel()::find($id);
        $employee->is_deleted = 1;
        $employee->deletedby_id = Auth::user()->id;
        $employee->update();

        if ($employee)
            return true;

        return false;
    }
}

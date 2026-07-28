<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserService
{
    // initialize protected model variables
    protected $model_user;

    public function __construct()
    {
        // set the model
        $this->model_user = new Repository(new User);
    }

    public function getUserSource()
    {
        $model = User::with('roles');
        $data = DataTables::of($model)
        ->addColumn('role', function ($item) {
            return $item->roles[0]->name??'';
        })
        ->addColumn('supplier', function ($item) {
            return $item->supplier_name->name??'';
        })
            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' href='users/edit/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $view_column    = "<a class='text-warning mr-2' href='users/view/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-eye'></i>View</a>";

                if(Auth::user()->can('users_edit'))
                $action_column .= $edit_column;

                if(Auth::user()->can('users_view'))
                $action_column .= $view_column;
                

                return $action_column;
            })
            ->rawColumns(['role','supplier','action'])
            ->make(true);
        return $data;
    }

    public function save($obj)
    {
        if ($obj['id'] != null && $obj['id'] != '') {
            $this->model_user->update($obj, $obj['id']);
            $saved_obj = $this->model_user->find($obj['id']);
        } else {
            $saved_obj = $this->model_user->create($obj);
        }

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    public function getById($id)
    {
        return $this->model_user->getModel()::with(['roles','permissions'])->find($id);
    }
}

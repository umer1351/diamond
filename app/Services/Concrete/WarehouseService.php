<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class WarehouseService
{
    // initialize protected model variables
    protected $model_warehouse;

    public function __construct()
    {
        // set the model
        $this->model_warehouse = new Repository(new Warehouse);
    }

    public function getWarehouseSource()
    {
        $model = $this->model_warehouse->getModel()::where('is_deleted', 0);
        $data = DataTables::of($model)
            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' href='warehouses/edit/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $view_column    = "<a class='text-warning mr-2' href='warehouses/view/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteWarehouse' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('warehouses_edit'))
                    $action_column .= $edit_column;

                if (Auth::user()->can('warehouses_view'))
                    $action_column .= $view_column;

                if (Auth::user()->can('warehouses_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
        return $data;
    }

    public function save($obj)
    {
        if ($obj['id'] != null && $obj['id'] != '') {
            $obj['updatedby_id'] = Auth::user()->id;
            $this->model_warehouse->update($obj, $obj['id']);
            $saved_obj = $this->model_warehouse->find($obj['id']);
        } else {
            $obj['createdby_id'] = Auth::user()->id;
            $saved_obj = $this->model_warehouse->create($obj);
        }

        if (!$saved_obj)
            return false;

        return true;
    }

    public function getById($id)
    {
        return $this->model_warehouse->getModel()::find($id);
    }
    // get all warehouse
    public function getAll()
    {
        return  $this->model_warehouse->getModel()::where('is_deleted', 0)->get();
    }
    public function deleteById($id)
    {
        $warehouse = $this->model_warehouse->getModel()::find($id);
        $warehouse->is_deleted = 1;
        $warehouse->deletedby_id = Auth::user()->id;
        $warehouse->update();

        if ($warehouse)
            return true;

        return false;
    }
}

<?php

namespace App\Services\Concrete;

use App\Models\OtherProduct;
use App\Models\OtherProductUnit;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OtherProductService
{
    protected $model_other_product;
    protected $model_other_product_unit;
    public function __construct()
    {
        // set the model
        $this->model_other_product = new Repository(new OtherProduct);
        $this->model_other_product_unit = new Repository(new OtherProductUnit);
    }
    //Other Product
    public function getSource()
    {
        $model = $this->model_other_product->getModel()::with('other_product_unit')->where('is_deleted', 0);
        $data = DataTables::of($model)
            ->addColumn('status', function ($item) {
                if (Auth::user()->can('other_product_status')){
                if ($item->is_active == 1) {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                } else {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                }
                return $status;
            }else{
                return 'N/A';
            }

            })
            ->addColumn('unit', function ($item) {
                return $item->other_product_unit->name??'';

            })
            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' id='editOtherProduct' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteOtherProduct' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                if (Auth::user()->can('other_product_edit'))
                    $action_column .= $edit_column;
                if (Auth::user()->can('other_product_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['status','unit', 'action'])
            ->make(true);
        return $data;
    }
    // get active all 
    public function getAllActiveOtherProduct()
    {
        return $this->model_other_product->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
    }
    // save 
    public function save($obj)
    {

        $obj['createdby_id'] = Auth::User()->id;

        $saved_obj = $this->model_other_product->create($obj);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // update
    public function update($obj)
    {

        $obj['updatedby_id'] = Auth::User()->id;
        $this->model_other_product->update($obj, $obj['id']);
        $saved_obj = $this->model_other_product->find($obj['id']);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // get by id
    public function getById($id)
    {
        $other_product = $this->model_other_product->getModel()::find($id);

        if (!$other_product)
            return false;

        return $other_product;
    }
    // status by id
    public function statusById($id)
    {
        $other_product = $this->model_other_product->getModel()::find($id);
        if ($other_product->is_active == 0) {
            $other_product->is_active = 1;
        } else {
            $other_product->is_active = 0;
        }
        $other_product->updatedby_id = Auth::user()->id;
        $other_product->update();

        if ($other_product)
            return true;

        return false;
    }
    // delete by id
    public function deleteById($id)
    {
        $other_product = $this->model_other_product->getModel()::find($id);
        $other_product->is_deleted = 1;
        $other_product->deletedby_id = Auth::user()->id;
        $other_product->update();

        if (!$other_product)
            return false;

        return $other_product;
    }

    // get active all unit
    public function getAllActiveOtherProductUnit()
    {
        return $this->model_other_product_unit->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
    }
}

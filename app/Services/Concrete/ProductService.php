<?php

namespace App\Services\Concrete;

use App\Models\Product;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    protected $model_product;
    public function __construct()
    {
        // set the model
        $this->model_product = new Repository(new Product);
    }
    //Product
    public function getProductSource()
    {
        $model = Product::withCount(['finishProducts as stock_count' => function ($query) {
            $query->where('is_deleted', 0)->where('is_active', 1);
        }])->where('is_deleted', 0);
        $data = DataTables::of($model)
            ->addColumn('stock', function ($item) {
                $manualStock = $item->getRawOriginal('stock');

                return $manualStock !== null && $manualStock !== ''
                    ? (int) $manualStock
                    : (int) ($item->stock_count ?? 0);
            })
            ->addColumn('description', function ($item) {
                return e(Str::limit((string) ($item->description ?? ''), 60));
            })
            ->addColumn('status', function ($item) {
                if (Auth::user()->can('products_status')){
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
            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' id='editProduct' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $delete_column    = "<a class='text-danger mr-2'  id='deleteProduct' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                if (Auth::user()->can('products_edit'))
                    $action_column .= $edit_column;
                if (Auth::user()->can('products_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        return $data;
    }
    // get all product
    public function getAllProduct()
    {
        return Product::where('is_deleted', 0)->get();
    }
    // get all product
    public function getAllActiveProduct()
    {
        return Product::where('is_deleted', 0)->where('is_active',1)->get();
    }
    // save Product
    public function saveProduct($obj)
    {

        $obj['createdby_id'] = Auth::User()->id;

        $saved_obj = $this->model_product->create($obj);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // update Product
    public function updateProduct($obj)
    {

        $obj['updatedby_id'] = Auth::User()->id;
        $this->model_product->update($obj, $obj['id']);
        $saved_obj = $this->model_product->find($obj['id']);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // get by id
    public function getProductById($id)
    {
        $product = $this->model_product->getModel()::find($id);

        if (!$product)
            return false;

        return $product;
    }
    // status by id
    public function statusById($id)
    {
        $product = $this->model_product->getModel()::find($id);
        if ($product->is_active == 0) {
            $product->is_active = 1;
        } else {
            $product->is_active = 0;
        }
        $product->updatedby_id = Auth::user()->id;
        $product->update();

        if ($product)
            return true;

        return false;
    }
    // delete by id
    public function deleteProductById($id)
    {
        $product = $this->model_product->getModel()::find($id);
        $product->is_deleted = 1;
        $product->deletedby_id = Auth::user()->id;
        $product->update();

        if (!$product)
            return false;

        return $product;
    }
}

<?php

namespace App\Services\Concrete;

use App\Models\OtherProduct;
use App\Models\StockTaking;
use App\Models\StockTakingDetail;
use App\Repository\Repository;
use App\Models\Transaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockTakingService
{
    // initialize protected model variables
    protected $model_stock_taking;
    protected $model_stock_taking_detail;
    protected $model_transaction;
    protected $model_warehouse;
    protected $model_other_product;

    protected $common_service;
    public function __construct()
    {
        // set the model
        $this->model_transaction = new Repository(new Transaction);
        $this->model_warehouse = new Repository(new Warehouse);
        $this->model_other_product = new Repository(new OtherProduct);
        $this->model_stock_taking = new Repository(new StockTaking);
        $this->model_stock_taking_detail = new Repository(new StockTakingDetail);

        $this->common_service = new CommonService();
    }

    public function getSource($obj)
    {
        $wh = [];
        if ($obj['stock_date'] != '') {
            $wh[] = ['stock_date', $obj['stock_date']];
        }
        if ($obj['warehouse_id'] != '') {
            $wh[] = ['warehouse_id', $obj['warehouse_id']];
        }
        if ($obj['is_opening_stock'] != '') {
            $wh[] = ['is_opening_stock', $obj['is_opening_stock']];
        }
        $model = $this->model_stock_taking->getModel()::with(
            [
                'warehouse_name'
            ]
        )
            ->where($wh)
            ->where('is_deleted', 0)
            ->orderBy('stock_date', 'DESC');

        $data = DataTables::of($model)
            ->addColumn('warehouse', function ($item) {
                return $item->warehouse_name->name ?? '';
            })
            ->addColumn('other_product', function ($item) {
                return $item->other_product->name ?? '';
            })
            ->addColumn('type', function ($item) {
                return ($item->is_opening_stock == 1) ? 'Opening Stock' : 'Stock Taking';
            })
            ->addColumn('created_at', function ($item) {
                return date('d-M-Y', strtotime($item->created_at));
            })
            ->addColumn('action', function ($item) {

                $action_column = '';
                $view_column    = "<a class='text-warning mr-2' target='_blank' href='" . url('/') . "/stock-taking/view/" . $item->id . "' ><i title='view' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteStockTaking' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                if (Auth::user()->can('stock_taking_view'))
                    $action_column .= $view_column;
                if (Auth::user()->can('stock_taking_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['warehouse', 'other_product', 'type', 'created_at', 'action'])
            ->make(true);
        return $data;
    }
    public function store($obj)
    {

        try {
            DB::beginTransaction();

            $stock_taking = new StockTaking;
            $stock_taking->warehouse_id = $obj['warehouse'];
            $stock_taking->stock_date = $obj['date'];
            $stock_taking->created_at = Carbon::now();
            $stock_taking->createdby_id =  Auth::user()->id;
            $stock_taking->save();
            $dataSet = [];
            $transaction_new = [];
            $transaction_reset = [];
            foreach ($obj['stockDetail'] as $item) {
                $dataSet[] = [
                    'stock_taking_id' => $stock_taking->id,
                    'other_product_id' => $item['productId'],
                    'quantity_in_stock' => $item['quantityInStock'],
                    'actual_quantity' => $item['actualQuantity'],
                    'createdby_id' => Auth::user()->id,
                ];
                $transaction_reset = [
                    "stock_taking_id" => $stock_taking->id,
                    "date" => $obj['date'] ?? Carbon::now(),
                    "warehouse_id" => $stock_taking->warehouse_id,
                    "other_product_id" => $item['productId'],
                    "qty" => $item['quantityInStock'],
                    "unit_price" => $item['unit_price'] ?? 0,
                    "createdby_id" => Auth::user()->id,
                    "type" => 2,
                    "created_at" => $obj['date'],
                    "updated_at" => $obj['date']
                ];
                $reset_stock_taking_transaction = $this->model_transaction->getModel()::create($transaction_reset);
                $transaction_new = [
                    "stock_taking_id" => $stock_taking->id,
                    "date" => $obj['date'] ?? Carbon::now(),
                    "stock_taking_link_id" => $reset_stock_taking_transaction->id,
                    "warehouse_id" => $stock_taking->warehouse_id,
                    "other_product_id" => $item['productId'],
                    "qty" => $item['actualQuantity'],
                    "unit_price" => $item['unit_price'] ?? 0,
                    "createdby_id" => Auth::user()->id,
                    "type" => 2,
                    "created_at" => $obj['date'],
                    "updated_at" => $obj['date']
                ];
                $this->model_transaction->getModel()::create($transaction_new);
            }

            $this->model_stock_taking_detail->getModel()::insert($dataSet);
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
    public function getById($id)
    {
        return $this->model_stock_taking->getModel()::with(['warehouse_name'])->find($id);
    }
    public function stockTakingDetailByStockId($stock_taking_id)
    {
        $stock_taking_detail = $this->model_stock_taking_detail->getModel()::with('other_product')->where('stock_taking_id', $stock_taking_id)->get();
        $data = [];
        $transactions = $this->model_transaction->getModel()::select('unit_price', 'other_product_id')->where('stock_taking_id', $stock_taking_id)->where('stock_taking_link_id', '!=', null)->get();
        foreach ($stock_taking_detail as $item) {
            $unit_price = $transactions->where('other_product_id', $item->other_product_id)->first();
            $data[] = [
                "code" => $item->other_product->code ?? '',
                "product_name" => $item->other_product->name ?? '',
                "unit_price" => $unit_price->unit_price ?? 0,
                "quantity_in_stock" => $item->quantity_in_stock ?? 0,
                "actual_quantity" => $item->actual_quantity ?? 0
            ];
        }
        return $data;
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            $stock_taking = $this->model_stock_taking->getModel()::find($id);
            $stock_taking->is_deleted = 1;
            $stock_taking->deletedby_id = Auth::user()->id;
            $stock_taking->update();

            $this->model_transaction->getModel()::where('stock_taking_id', $stock_taking->id)->update(['is_deleted' => 1, 'deletedby_id' => Auth::user()->id]);
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
}

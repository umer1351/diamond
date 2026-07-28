<?php

namespace App\Services\Concrete;

use App\Models\OtherProduct;
use App\Repository\Repository;
use App\Models\Transaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransactionService
{
      // initialize protected model variables
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

            $this->common_service = new CommonService();
      }

      public function getSource($obj)
      {
            $wh = [];
            if ($obj['warehouse_id'] != '') {
                  $wh[] = ['warehouse_id', $obj['warehouse_id']];
            }
            $model = Transaction::with([
                  'warehouse_name',
                  'other_product',
                  'other_sale',
                  'other_purchase'
            ])
                  ->where('date', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                  ->where('date', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                  ->where($wh)
                  ->where('is_deleted', 0);
            $data = DataTables::of($model)
                  ->addColumn('warehouse', function ($item) {
                        return $item->warehouse_name->name ?? '';
                  })
                  ->addColumn('other_product', function ($item) {
                        return $item->other_product->name ?? '';
                  })
                  ->addColumn('type', function ($item) {
                        if ($item->type == 0) {
                              return "Purchase";
                        } elseif ($item->type == 1) {
                              return "Sale";
                        } elseif ($item->type == 2) {
                              return "Stock Taking";
                        } elseif ($item->type == 3) {
                              return "Opening Stock";
                        }
                  })
                  ->addColumn('other_sale', function ($item) {
                        return $item->other_sale->other_sale_no ?? '';
                  })
                  ->addColumn('other_purchase', function ($item) {
                        return $item->other_purchase->other_purchase_no ?? '';
                  })
                  ->addColumn('stock_taking', function ($item) {
                        return $item->stock_taking->id ?? '';
                  })
                  ->addColumn('action', function ($item) {
                        $action_column = '';
                        $delete_column = "<a class='text-danger mr-2' id='deleteTransaction' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        if (Auth::user()->can('transaction_log_delete'))
                              $action_column .= $delete_column;
                        return $action_column;
                  })
                  ->rawColumns(['warehouse', 'other_product', 'type', 'other_sale', 'stock_taking', 'other_purchase', 'action'])
                  ->make(true);
            return $data;
      }

      public function deleteById($id)
      {
            $transaction = $this->model_transaction->getModel()::find($id);
            $transaction->is_deleted = 1;
            $transaction->deletedby_id = Auth::user()->id;
            $transaction->update();

            if ($transaction)
                  return true;

            return false;
      }
}

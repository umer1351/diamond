<?php

namespace App\Services\Concrete;

use App\Models\GoldRateType;
use App\Models\JobTask;
use App\Models\JobTaskDetail;
use App\Models\JournalEntry;
use App\Repository\Repository;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SaleOrder;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PurchaseOrderService
{
      // initialize protected model variables
      protected $model_purchase_order;
      protected $model_purchase_order_detail;
      protected $model_journal_entry;
      protected $model_sale_order;
      protected $model_job_task;
      protected $model_job_task_detail;

      protected $common_service;
      protected $journal_entry_service;
      public function __construct()
      {
            // set the model
            $this->model_purchase_order = new Repository(new PurchaseOrder);
            $this->model_purchase_order_detail = new Repository(new PurchaseOrderDetail);
            $this->model_journal_entry = new Repository(new JournalEntry);
            $this->model_sale_order = new Repository(new SaleOrder);
            $this->model_job_task = new Repository(new JobTask);
            $this->model_job_task_detail = new Repository(new JobTaskDetail);

            $this->common_service = new CommonService();
            $this->journal_entry_service = new JournalEntryService();
      }

      public function getSource($obj)
      {
            $wh = [];
            if ($obj['supplier_id'] != '') {
                  $wh[] = ['supplier_id', $obj['supplier_id']];
            }
            $model = $this->model_purchase_order->getModel()::has('PurchaseOrderDetail')
                  ->with(['sale_order', 'warehouse_name', 'supplier_name'])
                  ->where('is_deleted', 0)
                  ->whereBetween('purchase_order_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
                  ->where($wh);

            $data = DataTables::of($model)
                  ->addColumn('supplier_name', function ($item) {
                        return $item->supplier_name->name ?? '';
                  })
                  ->addColumn('sale_order', function ($item) {
                        return $item->sale_order->sale_order_no ?? '';
                  })
                  ->addColumn('warehouse_name', function ($item) {
                        return $item->warehouse_name->name ?? '';
                  })
                  ->addColumn('is_complete', function ($item) {
                        if ($item->is_complete == 1) {
                              $saled = '<span class=" badge badge-success mr-3">Yes</span>';
                        } else {
                              $saled = '<span class=" badge badge-danger mr-3">No</span>';
                        }
                        return $saled;
                  })
                  ->addColumn('status', function ($item) {
                        if ($item->status == 'Pending')
                              $status = "<span class='btn-warning p-1 text-white' style='border-radius: 8px;'>Pendding</span> ";
                        if ($item->status == 'Approved')
                              $status = "<span class='btn-success p-1 text-white' style='border-radius: 8px;'>Approved</span> ";
                        if ($item->status == 'Rejected')
                              $status = "<span class='btn-danger p-1 text-white' style='border-radius: 8px;'>Rejected</span> ";

                        return $status;
                  })
                  ->addColumn('action', function ($item) {

                        $action_column = '';
                        $print_column    = "<a class='text-info mr-2' href='purchase-order/print/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deletePurchaseOrder' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        $approve_column    = "<a class='text-success mr-2' id='approvePurchaseOrder' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Approve'><i title='Approve' class='nav-icon mr-2 fa fa-check'></i>Approve</a>";
                        $reject_column    = "<a class='text-danger mr-2' id='rejectPurchaseOrder' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Reject'><i title='Reject' class='nav-icon mr-2 fa fa-close'></i>Reject</a>";


                        if (Auth::user()->can('purchase_order_print'))
                              $action_column .= $print_column;

                        if (Auth::user()->can('purchase_order_delete') && $item->status == 'Pending')
                              $action_column .= $delete_column;
                        if (Auth::user()->can('purchase_order_approve') && $item->status == 'Pending')
                              $action_column .= $approve_column;

                        if (Auth::user()->can('purchase_order_reject') && $item->status == 'Pending')
                              $action_column .= $reject_column;

                        return $action_column;
                  })
                  ->rawColumns(['supplier_name', 'warehouse_name', 'status', 'sale_order', 'is_complete', 'action'])
                  ->addIndexColumn()
                  ->make(true);
            return $data;
      }

      public function getAllPurchaseOrder()
      {
            return $this->model_purchase_order->getModel()::with('supplier_name')
                  ->where('is_deleted', 0)
                  ->get();
      }
      public function savePurchaseOrder()
      {
            try {
                  DB::beginTransaction();
                  $obj = [
                        'purchase_order_no' => $this->common_service->generatePurchaseOrderNo(),
                        'createdby_id' => Auth::User()->id
                  ];
                  $saved_obj = $this->model_purchase_order->create($obj);

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return $saved_obj;
      }
      public function save($obj)
      {
            try {
                  DB::beginTransaction();

                  $PurchaseOrderDetail = json_decode($obj['purchaseOrderDetail'], true);
                  $PurchaseOrderObj = [
                        "purchase_order_date" => $obj['purchase_order_date'],
                        "reference_no" => $obj['reference_no'],
                        "delivery_date" => $obj['delivery_date'],
                        "supplier_id" => $obj['supplier_id'],
                        "warehouse_id" => $obj['warehouse_id'],
                        "total_qty" => count($PurchaseOrderDetail) ?? 0,
                        "sale_order_id" => $obj['sale_order_id'] ?? null,
                        "updatedby_id" => Auth::user()->id
                  ];
                  $purchase_order = $this->model_purchase_order->update($PurchaseOrderObj, $obj['id']);
                  foreach ($PurchaseOrderDetail as $item) {
                        $PurchaseOrderDetailObj = [
                              "purchase_order_id" => $obj['id'],
                              "product_id" => $item['product_id'] ?? '',
                              "category" => $item['category'] ?? '',
                              "design_no" => $item['design_no'] ?? '',
                              "net_weight" => $item['net_weight'] ?? 0.000,
                              "description" => $item['description'] ?? '',
                              "createdby_id" => Auth::user()->id
                        ];
                        $purchase_order_detail = $this->model_purchase_order_detail->create($PurchaseOrderDetailObj);
                  }
                  if ($obj['sale_order_id'] != '' && $obj['sale_order_id'] != null) {
                        $sale_order = $this->model_sale_order->getModel()::find($obj['sale_order_id']);
                        $sale_order->is_purchased = 1;
                        $sale_order->update();
                  }

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }

      public function getById($id)
      {
            return $this->model_purchase_order->getModel()::with(['supplier_name', 'warehouse_name'])->find($id);
      }

      public function purchaseOrderDetail($purchase_order_id)
      {
            $purchase_order_detail = $this->model_purchase_order_detail->getModel()::where('purchase_order_id', $purchase_order_id)
                  ->where('is_deleted', 0)->get();

            $data = [];
            foreach ($purchase_order_detail as $item) {
                  $data[] = [
                        "product_id" => $item->product_id ?? '',
                        "product" => $item->product->name ?? '',
                        "category" => $item->category ?? '',
                        "design_no" => $item->design_no ?? '',
                        "net_weight" => $item->net_weight ?? 0.000,
                        "description" => $item->description ?? ''
                  ];
            }

            return $data;
      }


      public function deleteById($purchase_order_id)
      {
            try {
                  DB::beginTransaction();

                  $purchase_order = $this->model_purchase_order->getModel()::find($purchase_order_id);

                  // sale update
                  $purchase_order->is_deleted = 1;
                  $purchase_order->deletedby_id = Auth::user()->id;
                  $purchase_order->update();

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }

      //approved
      public function approved($purchase_order_id)
      {
            try {
                  DB::beginTransaction();
                  $purchase_order = $this->model_purchase_order->getModel()::find($purchase_order_id);

                  if ($purchase_order->status == 'Pending') {
                        $purchase_order->status = 'Approved';
                        $purchase_order->approvedby_id = Auth::user()->user_id;
                        $purchase_order->updated_at = date("Y-m-d H:i:s");
                        $purchase_order->update();


                        // Job Task Create
                        $JobTaskObj = [
                              "job_task_no" => $this->common_service->generateJobTaskNo(),
                              "job_task_date" => date("Y-m-d H:i:s"),
                              "purchase_order_id" => $purchase_order->id,
                              "reference_no" => $purchase_order->reference_no,
                              "delivery_date" => $purchase_order->delivery_date,
                              "supplier_id" => $purchase_order->supplier_id,
                              "warehouse_id" => $purchase_order->warehouse_id,
                              "total_qty" =>  $purchase_order->total_qty ?? 0,
                              "sale_order_id" => $purchase_order->sale_order_id ?? null,
                              "createdby_id" => Auth::user()->id
                        ];
                        $job_task = $this->model_job_task->create($JobTaskObj);
                        $purchase_order_detail = $this->model_purchase_order_detail->getModel()::where('purchase_order_id', $purchase_order_id)
                              ->where('is_deleted', 0)->get();
                        foreach ($purchase_order_detail as $item) {
                              $JobTaskDetailObj = [
                                    "job_task_id" => $job_task->id,
                                    "product_id" => $item['product_id'] ?? '',
                                    "category" => $item['category'] ?? '',
                                    "design_no" => $item['design_no'] ?? '',
                                    "net_weight" => $item['net_weight'] ?? 0.000,
                                    "description" => $item['description'] ?? '',
                                    "createdby_id" => Auth::user()->id
                              ];
                              $job_task_detail = $this->model_job_task_detail->create($JobTaskDetailObj);
                        }
                  } else {
                        $msg = "Purchase order not pending!";
                        return $msg;
                  }

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }

            return true;
      }

      //rejected
      public function rejected($purchase_order_id)
      {
            try {
                  DB::beginTransaction();
                  $purchase_order = $this->model_purchase_order->getModel()::find($purchase_order_id);

                  if ($purchase_order->status == 'Pending') {
                        $purchase_order->status = 'Rejected';
                        $purchase_order->updatedby_id = Auth::user()->user_id;
                        $purchase_order->updated_at = date("Y-m-d H:i:s");
                        $purchase_order->update();
                  } else {
                        $msg = "Purchase order not pending!";
                        return $msg;
                  }

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }

            return true;
      }
}

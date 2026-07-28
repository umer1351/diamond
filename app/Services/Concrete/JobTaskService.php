<?php

namespace App\Services\Concrete;

use App\Models\JournalEntry;
use App\Repository\Repository;
use App\Models\JobTask;
use App\Models\JobTaskDetail;
use App\Models\SaleOrder;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JobTaskService
{
      // initialize protected model variables
      protected $model_job_task;
      protected $model_job_task_detail;
      protected $model_journal_entry;
      protected $model_sale_order;

      protected $common_service;
      protected $journal_entry_service;
      public function __construct()
      {
            // set the model
            $this->model_job_task = new Repository(new JobTask);
            $this->model_job_task_detail = new Repository(new JobTaskDetail);
            $this->model_journal_entry = new Repository(new JournalEntry);
            $this->model_sale_order = new Repository(new SaleOrder);

            $this->common_service = new CommonService();
            $this->journal_entry_service = new JournalEntryService();
      }

      public function getSource($obj)
      {
            $model = $this->model_job_task->getModel()::has('JobTaskDetail')
                  ->with(['sale_order', 'purchase_order', 'warehouse_name', 'supplier_name'])
                  ->where('is_deleted', 0)
                  ->whereBetween('job_task_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start'].' 00:00:00'))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'].' 23:59:59')))])
                  ->where('supplier_id',$obj['supplier_id'])
                  ->get();
            $data = DataTables::of($model)
                  ->addColumn('supplier_name', function ($item) {
                        return $item->supplier_name->name ?? '';
                  })
                  ->addColumn('sale_order', function ($item) {
                        return $item->sale_order->sale_order_no ?? '';
                  })
                  ->addColumn('purchase_order', function ($item) {
                        return $item->purchase_order->purchase_order_no ?? '';
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
                  ->addColumn('action', function ($item) {

                        $action_column = '';
                        $activity_column    = "<a class='text-primary mr-2' href='job-task-activity/" . $item->id . "'><i title='Activity' class='nav-icon mr-2 fa fa-star'></i>Activity</a>";
                        $print_column    = "<a class='text-info mr-2' href='job-task/print/" . $item->id . "'><i title='Print' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                        $complete_column    = "<a class='text-success mr-2' href='job-purchase/create/" . $item->id . "'><i title='Complete' class='nav-icon mr-2 fa fa-check'></i>Complete</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteJobTask' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";


                        if (Auth::user()->can('job_task_activity_access'))
                              $action_column .= $activity_column;
                        if (Auth::user()->can('job_task_print'))
                              $action_column .= $print_column;
                        if (Auth::user()->can('job_task_complete') && $item->is_complete==0)
                              $action_column .= $complete_column;
                        if (Auth::user()->can('job_task_delete'))
                              $action_column .= $delete_column;

                        return $action_column;
                  })
                  ->rawColumns(['supplier_name', 'warehouse_name', 'sale_order', 'is_complete', 'action'])
                  ->addIndexColumn()
                  ->make(true);
            return $data;
      }

      public function getAllJobTask()
      {
            return $this->model_job_task->getModel()::with('supplier_name')
                  ->where('is_deleted', 0)
                  ->get();
      }
      public function save($obj)
      {
            try {
                  DB::beginTransaction();

                  $JobTaskDetail = json_decode($obj['JobTaskDetail'], true);
                  $JobTaskObj = [
                        "job_task_date" => $obj['job_task_date'],
                        "supplier_id" => $obj['supplier_id'],
                        "warehouse_id" => $obj['warehouse_id'],
                        "total_qty" => count($JobTaskDetail) ?? 0,
                        "sale_order_id" => $obj['sale_order_id'] ?? null,
                        "updatedby_id" => Auth::user()->id
                  ];
                  $purchase_order = $this->model_job_task->update($JobTaskObj, $obj['id']);
                  foreach ($JobTaskDetail as $item) {
                        $JobTaskDetailObj = [
                              "purchase_order_id" => $obj['id'],
                              "category" => $item['category'] ?? '',
                              "design_no" => $item['design_no'] ?? '',
                              "net_weight" => $item['net_weight'] ?? 0.000,
                              "description" => $item['description'] ?? '',
                              "createdby_id" => Auth::user()->id
                        ];
                        $purchase_order_detail = $this->model_job_task_detail->create($JobTaskDetailObj);
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
            return $this->model_job_task->getModel()::with(['supplier_name','purchase_order', 'warehouse_name'])->find($id);
      }

      public function JobTaskDetail($job_task_id)
      {
            $job_task_detail = $this->model_job_task_detail->getModel()::with(['job_task', 'product'])
                  ->where('job_task_id', $job_task_id)
                  ->where('is_deleted', 0)->get();

            $data = [];
            foreach ($job_task_detail as $item) {
                  $data[] = [
                        "id" => $item->id ?? '',
                        "product_id" => $item->product_id ?? '',
                        "product" => $item->product->name ?? '',
                        "category" => $item->category ?? '',
                        "design_no" => $item->design_no ?? '',
                        "net_weight" => $item->net_weight ?? 0.000,
                        "description" => $item->description ?? '',
                        "purchase_order_id" => $item->job_task->purchase_order_id ?? '',
                        "sale_order_id" => $item->job_task->sale_order_id ?? '',
                        "supplier_id" => $item->job_task->supplier_id ?? '',
                        "warehouse_id" => $item->job_task->warehouse_id ?? '',
                  ];
            }

            return $data;
      }


      public function deleteById($purchase_order_id)
      {
            try {
                  DB::beginTransaction();

                  $purchase_order = $this->model_job_task->getModel()::find($purchase_order_id);

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
}

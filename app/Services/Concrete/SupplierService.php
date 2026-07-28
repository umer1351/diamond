<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SupplierService
{
    // initialize protected model variables
    protected $model_supplier;

    protected $journal_entry_service;

    public function __construct()
    {
        // set the model
        $this->model_supplier = new Repository(new Supplier);

        $this->journal_entry_service = new JournalEntryService;
    }

    public function getSupplierSource()
    {
        $model = $this->model_supplier->getModel()::with('account_name')->where('is_deleted', 0);

        $data = DataTables::of($model)
            ->addColumn('type', function ($item) {
                switch ($item->type) {
                    case 0:
                        return 'Supplier';
                    case 1:
                        return 'Karigar';
                    case 3:
                        return 'Both';
                    default:
                        return 'N/A';
                }
            })
            ->addColumn('account', function ($item) {
                $name = $item->account_name->name ?? '';
                $code = $item->account_name->code ?? '';
                return $code . ' ' . $name;
            })
            ->addColumn('account_au', function ($item) {
                $name = $item->account_au_name->name ?? '';
                $code = $item->account_au_name->code ?? '';
                return $code . ' ' . $name;
            })
            ->addColumn('account_dollar', function ($item) {
                $name = $item->account_dollar_name->name ?? '';
                $code = $item->account_dollar_name->code ?? '';
                return $code . ' ' . $name;
            })
            ->addColumn('balance', function ($item) {
                if($item->account_id!=null){
                    $balance = $this->journal_entry_service->getSupplierBalanceByAccountId($item->id,$item->account_id,0);
                    return ($balance>0)?"<span class='btn-success pl-1'> <i class='fa fa-arrow-up'></i>".$balance."</span> ":(($balance<0)?"<span class='btn-danger pl-1'> <i class='fa fa-arrow-down'></i>".$balance."</span>":"<span class='btn-primary pl-1'> <i class='fa fa-arrows'></i>".(($balance==null)?0:$balance)."</span>");
                }else{
                    return "<span class='btn-danger pl-1'> <i class='fa fa-arrows'></i>0</span>";
                }
            })
            ->addColumn('balance_au', function ($item) {
                if($item->account_id!=null){
                    $balance = $this->journal_entry_service->getSupplierBalanceByAccountId($item->id,$item->account_id,1);
                    return ($balance>0)?"<span class='btn-success pl-1'> <i class='fa fa-arrow-up'></i>".$balance."</span> ":(($balance<0)?"<span class='btn-danger pl-1'> <i class='fa fa-arrow-down'></i>".$balance."</span>":"<span class='btn-primary pl-1'> <i class='fa fa-arrows'></i>".(($balance==null)?0:$balance)."</span>");
                }else{
                    return "<span class='btn-danger pl-1'> <i class='fa fa-arrows'></i>0</span>";
                }
            })
            ->addColumn('balance_dollar', function ($item) {
                if($item->account_id!=null){
                    $balance = $this->journal_entry_service->getSupplierBalanceByAccountId($item->id,$item->account_id,2);
                    return ($balance>0)?"<span class='btn-success pl-1'> <i class='fa fa-arrow-up'></i>".$balance."</span> ":(($balance<0)?"<span class='btn-danger pl-1'> <i class='fa fa-arrow-down'></i>".$balance."</span>":"<span class='btn-primary pl-1'> <i class='fa fa-arrows'></i>".(($balance==null)?0:$balance)."</span>");
                }else{
                    return "<span class='btn-danger pl-1'> <i class='fa fa-arrows'></i>0</span>";
                }
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
                $edit_column    = "<a class='text-success mr-2' href='suppliers/edit/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                // $view_column    = "<a class='text-warning mr-2' href='suppliers/view/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteSupplier' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('suppliers_edit'))
                    $action_column .= $edit_column;


                if (Auth::user()->can('suppliers_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['type', 'account', 'status','balance','balance_au','balance_dollar', 'action'])
            ->make(true);
        return $data;
    }

    public function getAllActiveSupplier()
    {
        return $this->model_supplier->getModel()::with('account_name')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->get();
    }

    public function save($obj)
    {
        if ($obj['id'] != null && $obj['id'] != '') {
            $obj['updatedby_id'] = Auth::user()->id;
            $this->model_supplier->update($obj, $obj['id']);
            $saved_obj = $this->model_supplier->find($obj['id']);
        } else {
            $obj['createdby_id'] = Auth::user()->id;
            $saved_obj = $this->model_supplier->create($obj);
        }

        if (!$saved_obj)
            return false;

        return true;
    }

    public function getById($id)
    {
        return $this->model_supplier->getModel()::find($id);
    }

    public function statusById($id)
    {
        $supplier = $this->model_supplier->getModel()::find($id);
        if ($supplier->is_active == 0) {
            $supplier->is_active = 1;
        } else {
            $supplier->is_active = 0;
        }
        $supplier->updatedby_id = Auth::user()->id;
        $supplier->update();

        if ($supplier)
            return true;

        return false;
    }

    public function deleteById($id)
    {
        $supplier = $this->model_supplier->getModel()::find($id);
        $supplier->is_deleted = 1;
        $supplier->deletedby_id = Auth::user()->id;
        $supplier->update();

        if ($supplier)
            return true;

        return false;
    }
}

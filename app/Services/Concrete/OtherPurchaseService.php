<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Repository\Repository;
use App\Models\OtherPurchase;
use App\Models\OtherPurchaseDetail;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OtherPurchaseService
{
    // initialize protected model variables
    protected $model_other_purchase;
    protected $model_other_purchase_detail;
    protected $model_journal_entry;
    protected $model_supplier_payment;

    protected $common_service;
    protected $journal_entry_service;
    public function __construct()
    {
        // set the model
        $this->model_other_purchase = new Repository(new OtherPurchase);
        $this->model_other_purchase_detail = new Repository(new OtherPurchaseDetail);
        $this->model_journal_entry = new Repository(new JournalEntry);
        $this->model_supplier_payment = new Repository(new SupplierPayment);

        $this->common_service = new CommonService();
        $this->journal_entry_service = new JournalEntryService();
    }

    public function getSource($obj)
    {
        $wh = [];
        if ($obj['posted'] != '') {
            $wh[] = ['posted', $obj['posted']];
        }
        if ($obj['supplier_id'] != '') {
            $wh[] = ['supplier_id', $obj['supplier_id']];
        }
        $model = $this->model_other_purchase->getModel()::has('OtherPurchaseDetail')->with('supplier_name')->where('is_deleted', 0)
            ->whereBetween('other_purchase_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
            ->where($wh);

        $data = DataTables::of($model)
            ->addColumn('check_box', function ($item) {
                if ($item->posted != 1)
                    return '<input type="checkbox" class="sub_chk" value="' . $item->id . '" data-id="' . $item->id . '" >';
            })
            ->addColumn('posted', function ($item) {
                $badge_color = $item->posted == 0 ? 'badge-danger' : 'badge-success';
                $badge_text = $item->posted == 0 ? 'Unposted' : 'Posted';
                return '<span class="badge ' . $badge_color . '">' . $badge_text . '</span>';
            })
            ->addColumn('supplier', function ($item) {
                return $item->supplier_name->name ?? '';
            })
            ->addColumn('action', function ($item) {

                $jvs = '';
                if ($item->jv_id != null)
                    $jvs .= "filter[]=" . $item->jv_id . "";

                if ($item->paid_jv_id != null)
                    $jvs .= "&filter[]=" . $item->paid_jv_id . "";

                $action_column = '';
                $unpost = '<a class="text text-danger" id="unpost" data-toggle="tooltip" data-id="' . $item->id . '" data-original-title="Unpost" href="javascript:void(0)"><i class="fa fa-repeat"></i>Unpost</a>';
                $print_column    = "<a class='text-info mr-2' href='other-purchase/print/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteOtherPurchase' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";


                if (Auth::user()->can('other_purchase_print'))
                    $action_column .= $print_column;
                if (Auth::user()->can('other_purchase_unpost') && $item->posted == 1)
                    $action_column .= $unpost;
                if (Auth::user()->can('other_purchase_jvs') && $item->posted == 1)
                    $action_column .= $all_print_column;

                if (Auth::user()->can('other_purchase_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['check_box', 'supplier', 'posted', 'action'])
            ->addIndexColumn()
            ->make(true);
        return $data;
    }

    public function getAllOtherPurchase()
    {
        return $this->model_other_purchase->getModel()::with('supplier_name')
            ->where('is_deleted', 0)
            ->get();
    }
    public function saveOtherPurchase()
    {
        try {
            DB::beginTransaction();
            $obj = [
                'other_purchase_no' => $this->common_service->generateOtherPurchaseNo(),
                'createdby_id' => Auth::User()->id
            ];
            $saved_obj = $this->model_other_purchase->create($obj);

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return $saved_obj;
    }
    public function save($obj)
    {
        dd($obj);
        try {
            DB::beginTransaction();
            $otherPurchaseDetail = json_decode($obj['otherProductDetail']);
            $otherPurchaseObj = [
                "other_purchase_date" => $obj['other_purchase_date'],
                "supplier_id" => $obj['supplier_id'],
                "total_qty" => $obj['total_qty'] ?? 0,
                "warehouse_id" => $obj['warehouse_id'] ?? null,
                "purchase_account_id" => $obj['purchase_account_id'] ?? null,
                "total" => $obj['total'],
                "paid" => $obj['paid'] ?? 0,
                "paid_account_id" => $obj['paid_account_id'] ?? null,
                "updatedby_id" => Auth::user()->id
            ];
            $total_qty = 0;
            foreach ($otherPurchaseDetail as $item) {
                $otherPurchaseDetailObj = [
                    "other_purchase_id" => $obj['id'],
                    "other_product_id" => $item->other_product_id ?? '',
                    "qty" => $item->qty ?? 0.000,
                    "unit_price" => $item->unit_price ?? 0.000,
                    "total_amount" => $item->total_amount,
                    "createdby_id" => Auth::user()->id
                ];
                $total_qty = $total_qty + $item->qty;
                $other_purchase_detail = $this->model_other_purchase_detail->create($otherPurchaseDetailObj);
            }
            $otherPurchaseObj['total_qty'] = $total_qty;
            $other_purchase = $this->model_other_purchase->update($otherPurchaseObj, $obj['id']);
            DB::commit();
        } catch (Exception $e) {
            return $e;
        }

        return true;
    }

    public function getById($id)
    {
        return $this->model_other_purchase->getModel()::with(['supplier_name', 'other_product'])->find($id);
    }

    public function otherPurchaseDetail($other_purchase_id)
    {
        $other_purchase_detail = $this->model_other_purchase_detail->getModel()::with([
            'other_product'
        ])
            ->where('other_purchase_id', $other_purchase_id)
            ->where('is_deleted', 0)->get();

        $data = [];
        foreach ($other_purchase_detail as $item) {
            $data[] = [
                "code" => $item->other_product->code ?? '',
                "product" => $item->other_product->name ?? '',
                "unit" => $item->other_product->other_product_unit->name ?? '',
                "unit_price" => $item->unit_price ?? 0,
                "qty" => $item->qty,
                "total_amount" => $item->total_amount
            ];
        }

        return $data;
    }

    public function post($obj)
    {

        try {
            DB::beginTransaction();

            foreach ($obj['other_purchase'] as $item) {

                $journal_entry_supplier_payment = null;
                $other_purchase_voucher = null;
                $journal_entry_supplier_payment = null;

                $other_purchase = $this->model_other_purchase->getModel()::with([
                    'supplier_name',
                    'supplier_name.account_name',
                    'purchase_account',
                    'OtherPurchaseDetail',
                    'paid_account'
                ])
                    ->find($item);

                $supplier =  $other_purchase->supplier_name;

                //==============  Create Purchase Inventory Transaction

                $this->createOtherPurchaseInventoryTransaction(
                    $other_purchase->OtherPurchaseDetail,
                    $other_purchase->other_purchase_date,
                    $other_purchase->bill_no,
                    $other_purchase->warehouse_id
                );

                $debit_amount = str_replace(',', '', $other_purchase->total);

                //============== Create Purchase Voucher
                $other_purchase_voucher = $this->createOtherPurchaseVoucher(
                    $other_purchase->other_purchase_no,
                    $other_purchase->other_purchase_date,
                    $other_purchase->bill_no,
                    $other_purchase->supplier_name,
                    $other_purchase->purchase_account,
                    $debit_amount
                );

                // paid Amount
                if ($other_purchase->paid > 0) {
                    if ($other_purchase->paid_account_id == null || $other_purchase->paid_account_id == '') {
                        $msg = 'Paid amount is greater then 0 but paid account not select!';
                        return $msg;
                    }

                    // supplier Payment Add
                    $paid_amount = str_replace(',', '', $other_purchase->paid);

                    //============== Create Purchase supplier Payment Voucher
                    $journal_entry_supplier_payment = $this->createOtherPurchaseSupplierPaymentVoucher(
                        $other_purchase->other_purchase_no,
                        $other_purchase->other_purchase_date,
                        $other_purchase->bill_no,
                        $supplier,
                        $other_purchase->paid_account,
                        $supplier->account_name,
                        $paid_amount
                    );

                    //============== Create Purchase Supplier Payment
                    $supplier_payment = $this->createOtherPurchaseSupplierPayment(
                        $journal_entry_supplier_payment,
                        $other_purchase->other_purchase_no,
                        $other_purchase->other_purchase_date,
                        $supplier,
                        $other_purchase->paid_account,
                        $paid_amount,
                        $other_purchase->tax_amount ?? 0,
                        $other_purchase->id
                    );
                }
                $other_purchase->posted = 1;
                $other_purchase->jv_id = $other_purchase_voucher;
                $other_purchase->paid_jv_id = $journal_entry_supplier_payment;
                $other_purchase->supplier_payment_id = $supplier_payment;
                $other_purchase->update();
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
    public function unpost($other_purchase_id)
    {
        try {
            DB::beginTransaction();

            $other_purchase = $this->model_other_purchase->getModel()::find($other_purchase_id);

            // Journal entry delete
            $journal_entry = $this->model_journal_entry->getModel()::find($other_purchase->jv_id);
            $journal_entry->is_deleted = 1;
            $journal_entry->deletedby_id = Auth::user()->id;
            $journal_entry->update();

            if ($other_purchase->paid_jv_id != null) {
                // Journal entry delete
                $paid_journal_entry = $this->model_journal_entry->getModel()::find($other_purchase->paid_jv_id);
                $paid_journal_entry->is_deleted = 1;
                $paid_journal_entry->deletedby_id = Auth::user()->id;
                $paid_journal_entry->update();
            }

            if ($other_purchase->supplier_payment_id != null) {
                // Journal entry delete
                $supplier_payment = $this->model_supplier_payment->getModel()::find($other_purchase->supplier_payment_id);
                $supplier_payment->is_deleted = 1;
                $supplier_payment->deletedby_id = Auth::user()->id;
                $supplier_payment->update();
            }

            // other purchase update
            $other_purchase->posted = 0;
            $other_purchase->jv_id = Null;
            $other_purchase->paid_jv_id = Null;
            $other_purchase->supplier_payment_id = Null;
            $other_purchase->update();

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    public function deleteById($other_purchase_id)
    {
        try {
            DB::beginTransaction();

            $other_purchase = $this->model_other_purchase->getModel()::find($other_purchase_id);

            // Journal entry delete
            $journal_entry = $this->model_journal_entry->getModel()::find($other_purchase->jv_id);
            $journal_entry->is_deleted = 1;
            $journal_entry->deletedby_id = Auth::user()->id;
            $journal_entry->update();

            if ($other_purchase->paid_jv_id != null) {
                // Journal entry delete
                $paid_journal_entry = $this->model_journal_entry->getModel()::find($other_purchase->paid_jv_id);
                $paid_journal_entry->is_deleted = 1;
                $paid_journal_entry->deletedby_id = Auth::user()->id;
                $paid_journal_entry->update();
            }

            if ($other_purchase->supplier_payment_id != null) {
                // Journal entry delete
                $supplier_payment = $this->model_supplier_payment->getModel()::find($other_purchase->supplier_payment_id);
                $supplier_payment->is_deleted = 1;
                $supplier_payment->deletedby_id = Auth::user()->id;
                $supplier_payment->update();
            }

            // other purchase update
            $other_purchase->posted = 0;
            $other_purchase->jv_id = Null;
            $other_purchase->paid_jv_id = Null;
            $other_purchase->supplier_payment_id = Null;
            $other_purchase->update();

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }


    public function createOtherPurchaseVoucher($other_purchase_no, $other_purchase_date, $bill_no, $supplier, $purchase_account, $amount)
    {
        $journal = Journal::find(config('enum.PV'));
        $other_purchase_date = date("Y-m-d", strtotime(str_replace('/', '-', $other_purchase_date)));
        // Add journal entry
        $data = [
            "date" => $other_purchase_date,
            "prefix" => $journal->prefix,
            "journal_id" => $journal->id
        ];
        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry = new JournalEntry;
        $journal_entry->journal_id = $journal->id;
        $journal_entry->supplier_id = $supplier->id;
        $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $other_purchase_date)));
        $journal_entry->reference = 'Date :' . $other_purchase_date . ' other purchase ' . $other_purchase_no . '. Supplier is ' . $supplier->name;
        $journal_entry->entryNum = $entryNum;
        $journal_entry->createdby_id = Auth::User()->id;
        $journal_entry->save();

        $journal_entry_id = $journal_entry->id ?? null;

        // Purchase Account
        if ($purchase_account->id == null || $purchase_account->id == '') {
            $msg = 'Purchase Account not selected please attach account then post again!';
            return $msg;
        }
        // PKR (Debit)
        $this->journal_entry_service->saveJVDetail(
            0, // currency 0 for PKR, 1 for AU, 2 for Dollar
            $journal_entry_id, // journal entry id
            'Purchase Amount From Purchase Debit Entry', //explaination
            $bill_no, //bill no
            0, // check no or 0
            $other_purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $amount, //amount
            $purchase_account->id, // account id
            $purchase_account->code, // account code
            Auth::User()->id //created by id
        );

        // credit Amount
        if ($supplier->account_id == null || $supplier->account_id == '') {
            $msg = 'Supplier Account has no account please attach account then post again!';
            return $msg;
        }
        $supplier_account = Account::find($supplier->account_id);
        // PKR (Credit)
        $this->journal_entry_service->saveJVDetail(
            0, // currency 0 for PKR, 1 for AU, 2 for Dollar
            $journal_entry_id, // journal entry id
            'Credit Amount From Purchase Credit Entry from ' . $supplier->name, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $other_purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $amount, //amount
            $supplier_account->id, // account id
            $supplier_account->code, // account code
            Auth::User()->id //created by id
        );

        return $journal_entry_id;
    }

    public function createOtherPurchaseInventoryTransaction($other_purchase_detail, $other_purchase_date, $bill_no, $warehouse_id)
    {
        foreach ($other_purchase_detail as $index => $item) {

            $obj = [
                "other_purchase_id" => $item->other_purchase_id,
                "date" => $other_purchase_date ?? Carbon::now(),
                "bill_no" => $bill_no,
                "warehouse_id" => $warehouse_id,
                "other_product_id" => $item->other_product_id,
                "qty" => $item->qty ?? 0,
                "unit_price" => $item->unit_price ?? 0,
                "createdby_id" => Auth::User()->id,
                "type" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ];

            $transation = Transaction::create($obj);
        }
    }

    public function createOtherPurchaseSupplierPaymentVoucher(
        $other_purchase_no,
        $other_purchase_date,
        $bill_no,
        $supplier,
        $paid_account,
        $supplier_account,
        $paid_amount
    ) {

        $journal_type = ($paid_account->is_cash_account == 1) ? config('enum.CPV') : config('enum.BPV');
        $journal_supplier = Journal::find($journal_type);
        // Add journal entry
        $data = [
            "date" => $other_purchase_date,
            "prefix" => $journal_supplier->prefix,
            "journal_id" => $journal_supplier->id
        ];
        $entryNum_supplier = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry_supplier = new JournalEntry;
        $journal_entry_supplier->journal_id = $journal_supplier->id;
        $journal_entry_supplier->supplier_id = $supplier->id;
        $journal_entry_supplier->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $other_purchase_date)));
        $journal_entry_supplier->reference = 'Date :' . $other_purchase_date . ' Against OPO. ' . $other_purchase_no;
        $journal_entry_supplier->entryNum = $entryNum_supplier;
        $journal_entry_supplier->createdby_id = Auth::User()->id;
        $journal_entry_supplier->save();

        $paid_jv_id = $journal_entry_supplier->id;

        // Journal entry detail (Credit)
        $this->journal_entry_service->saveJVDetail(
            0, // currency 0 for PKR, 1 for AU, 2 for Dollar
            $paid_jv_id, // journal entry id
            'Supplier Paid Payment Against Other Purchase Credit', //explaination
            $bill_no, //bill no
            0, // check no or 0
            $other_purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $paid_amount, //amount
            $paid_account->id, // account id
            $paid_account->code, // account code
            Auth::User()->id //created by id
        );

        // Journal entry detail (Debit)
        $this->journal_entry_service->saveJVDetail(
            0, // currency 0 for PKR, 1 for AU, 2 for Dollar
            $paid_jv_id, // journal entry id
            'Supplier Paid Payment Against Purchase Debit', //explaination
            $bill_no, //bill no
            0, // check no or 0
            $other_purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $paid_amount, //amount
            $supplier_account->id, // account id
            $supplier_account->code, // account code
            Auth::User()->id //created by id
        );

        return $paid_jv_id;
    }

    public function createOtherPurchaseSupplierPayment(
        $journal_entry_id,
        $other_purchase_no,
        $other_purchase_date,
        $supplier,
        $paid_account,
        $paid_amount,
        $tax_amount,
        $other_purchase_id
    ) {
        // Supplier Payment Add
        $supplier_payment_data = [
            'supplier_id' => $supplier->id,
            'account_id' => $paid_account->id,
            'payment_date' => $other_purchase_date,
            'currency' => 0,
            'cheque_ref' => 'Date :' . $other_purchase_date . ' Against OPO. ' . $other_purchase_no,
            'sub_total' => $paid_amount,
            'total' => $paid_amount,
            'tax' => 0,
            'tax_amount' => $tax_amount ?? 0,
            'tax_account_id' => Null,
            'other_purchase_id' => $other_purchase_id,
            'posted' => 1,
            'jv_id' => $journal_entry_id,
            'createdby_id' => Auth::User()->id
        ];

        $supplier_payment =  SupplierPayment::create($supplier_payment_data);

        return $supplier_payment->id;
    }
}

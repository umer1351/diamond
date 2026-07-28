<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Customer;
use App\Models\FinishProduct;
use App\Models\JobPurchase;
use App\Models\JobPurchaseDetail;
use App\Models\JobPurchaseDetailBead;
use App\Models\JobPurchaseDetailDiamond;
use App\Models\JobPurchaseDetailStone;
use App\Models\JobTask;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\PurchaseOrder;
use App\Repository\Repository;
use App\Models\Sale;
use App\Models\RattiKaatDetail;
use App\Models\SaleDetail;
use App\Models\SaleDetailBead;
use App\Models\SaleDetailDiamond;
use App\Models\SaleDetailStone;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JobPurchaseService
{
    // initialize protected model variables
    protected $model_job_purchase;
    protected $model_job_purchase_detail;
    protected $model_job_purchase_detail_bead;
    protected $model_job_purchase_detail_stone;
    protected $model_job_purchase_detail_diamond;
    protected $model_journal_entry;
    protected $model_purchase_order;
    protected $model_job_task;

    protected $common_service;
    protected $supplier_payment_service;
    protected $journal_entry_service;
    public function __construct()
    {
        // set the model
        $this->model_job_purchase = new Repository(new JobPurchase);
        $this->model_job_purchase_detail = new Repository(new JobPurchaseDetail);
        $this->model_job_purchase_detail_bead = new Repository(new JobPurchaseDetailBead);
        $this->model_job_purchase_detail_stone = new Repository(new JobPurchaseDetailStone);
        $this->model_job_purchase_detail_diamond = new Repository(new JobPurchaseDetailDiamond);
        $this->model_journal_entry = new Repository(new JournalEntry);
        $this->model_purchase_order = new Repository(new PurchaseOrder);
        $this->model_job_task = new Repository(new JobTask);

        $this->common_service = new CommonService();
        $this->supplier_payment_service = new SupplierPaymentService();
        $this->journal_entry_service = new JournalEntryService();
    }

    public function getSource($obj)
    {
        $wh = [];
        if ($obj['posted'] != '') {
            $wh[] = ['is_posted', $obj['posted']];
        }
        if ($obj['supplier_id'] != '') {
            $wh[] = ['supplier_id', $obj['supplier_id']];
        }
        $model = $this->model_job_purchase->getModel()::with(
            [
                'purchase_order',
                'sale_order',
                'supplier_name'
            ]
        )->has('JobPurchaseDetail')->where('is_deleted', 0)
            ->whereBetween('job_purchase_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
            ->where($wh);

        $data = DataTables::of($model)
            ->addColumn('check_box', function ($item) {
                if ($item->is_posted != 1)
                    return '<input type="checkbox" class="sub_chk" value="' . $item->id . '" data-id="' . $item->id . '" >';
            })
            ->addColumn('purchase_order', function ($item) {
                return $item->purchase_order->purchase_order_no ?? '';
            })
            ->addColumn('sale_order', function ($item) {
                return $item->sale_order->sale_order_no ?? '';
            })
            ->addColumn('customer_name', function ($item) {
                return $item->sale_order->customer_name->name ?? '';
            })
            ->addColumn('supplier_name', function ($item) {
                return $item->supplier_name->name ?? '';
            })
            ->addColumn('posted', function ($item) {
                $badge_color = $item->is_posted == 0 ? 'badge-danger' : 'badge-success';
                $badge_text = $item->is_posted == 0 ? 'Unposted' : 'Posted';
                return '<span class="badge ' . $badge_color . '">' . $badge_text . '</span>';
            })
            ->addColumn('saled', function ($item) {
                $badge_color = $item->is_saled == 0 ? 'badge-danger' : 'badge-success';
                $badge_text = $item->is_saled == 0 ? 'No' : 'Yes';
                return '<span class="badge ' . $badge_color . '">' . $badge_text . '</span>';
            })
            ->addColumn('action', function ($item) {

                $jvs = '';
                if ($item->jv_id != null)
                    $jvs .= "filter[]=" . $item->jv_id . "";
                if ($item->jv_au_id != null)
                    $jvs .= "&filter[]=" . $item->jv_au_id . "";
                if ($item->jv_dollar_id != null)
                    $jvs .= "&filter[]=" . $item->jv_dollar_id . "";
                if ($item->jv_recieved_id != null)
                    $jvs .= "&filter[]=" . $item->jv_recieved_id . "";

                $action_column = '';
                $unpost = '<a class="text text-danger" id="unpost" data-toggle="tooltip" data-id="' . $item->id . '" data-original-title="Unpost" href="javascript:void(0)"><i class="fa fa-repeat"></i>Unpost</a>';
                $print_column    = "<a class='text-info mr-2' href='job-purchase/print/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteJobPurchase' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                // if (Auth::user()->can('customers_edit'))
                //     $action_column .= $edit_column;

                if (Auth::user()->can('job_purchase_print'))
                    $action_column .= $print_column;
                if (Auth::user()->can('job_purchase_unpost') && $item->is_posted == 1)
                    $action_column .= $unpost;
                if (Auth::user()->can('job_purchase_jvs') && $item->is_posted == 1)
                    $action_column .= $all_print_column;

                if (Auth::user()->can('job_purchase_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['check_box', 'purchase_order', 'sale_order', 'customer_name', 'supplier_name', 'posted', 'saled', 'action'])
            ->addIndexColumn()
            ->make(true);
        return $data;
    }

    public function getAllJobPurchase()
    {
        return $this->model_job_purchase->getModel()::with(
            [
                'purchase_order',
                'sale_order',
                'supplier_name'
            ]
        )
            ->where('is_deleted', 0)
            ->get();
    }
    public function save($obj)
    {

        try {
            DB::beginTransaction();
            $jobPurchaseObj = [
                "job_purchase_no" => $obj['job_purchase_no'],
                "job_purchase_date" => $obj['job_purchase_date'],
                "job_task_id" => $obj['job_task_id'],
                "purchase_order_id" => $obj['purchase_order_id'],
                "sale_order_id" => $obj['sale_order_id'],
                "supplier_id" => $obj['supplier_id'],
                "warehouse_id" => $obj['warehouse_id'],
                "reference" => $obj['reference'],
                "total_recieved_au" => $obj['total_recieved_au'] ?? 0,
                "total" => $obj['total'],
                "total_au" => $obj['total_au'],
                "total_dollar" => $obj['total_dollar'],
                "createdby_id" => Auth::user()->id
            ];
            $job_purchase = $this->model_job_purchase->create($jobPurchaseObj);


            $jobPurchaseDetail = json_decode($obj['productDetail'], true);
            foreach ($jobPurchaseDetail as $item) {
                $jobPurchaseDetailObj = $item;
                $jobPurchaseDetailObj['job_purchase_id'] = $job_purchase->id;
                $jobPurchaseDetailObj['createdby_id'] = Auth::user()->id;
                $job_purchase_detail = $this->model_job_purchase_detail->create($jobPurchaseDetailObj);

                foreach ($item['beadDetail'] as $item1) {
                    $jobPurchaseDetailBead = $item1;
                    $jobPurchaseDetailBead['job_purchase_detail_id'] = $job_purchase_detail->id;
                    $jobPurchaseDetailBead['createdby_id'] = Auth::user()->id;
                    $this->model_job_purchase_detail_bead->create($jobPurchaseDetailBead);
                }

                // Stone Detail
                foreach ($item['stonesDetail'] as $item1) {
                    $jobPurchaseDetailStone = $item1;
                    $jobPurchaseDetailStone['job_purchase_detail_id'] = $job_purchase_detail->id;
                    $jobPurchaseDetailStone['createdby_id'] = Auth::user()->id;
                    $this->model_job_purchase_detail_stone->create($jobPurchaseDetailStone);
                }

                // Diamond Detail
                foreach ($item['diamondDetail'] as $item1) {
                    $jobPurchaseDetailDiamond = $item1;
                    $jobPurchaseDetailDiamond['job_purchase_detail_id'] = $job_purchase_detail->id;
                    $jobPurchaseDetailDiamond['createdby_id'] = Auth::user()->id;
                    $this->model_job_purchase_detail_diamond->create($jobPurchaseDetailDiamond);
                }
            }

            $job_task = $this->model_job_task->find($obj['job_task_id']);
            $job_task->is_complete = 1;
            $job_task->updatedby_id = Auth::user()->id;
            $job_task->update();


            DB::commit();
        } catch (Exception $e) {
            return $e;
        }

        return true;
    }

    public function getById($id)
    {
        return $this->model_job_purchase->getModel()::with(
            [
                'purchase_order',
                'sale_order',
                'created_by',
                'supplier_name'
            ]
        )->find($id);
    }

    public function jobPurchaseDetail($job_purchase_id)
    {
        $job_purchase_detail = $this->model_job_purchase_detail->getModel()::with('product')
            ->where('job_purchase_id', $job_purchase_id)->where('is_deleted', 0)->get();

        $data = [];
        foreach ($job_purchase_detail as $index => $item) {
            $bead_detail = $this->model_job_purchase_detail_bead->getModel()::where('job_purchase_detail_id', $item->id)
                ->where('is_deleted', 0)->get();
            $stone_detail = $this->model_job_purchase_detail_stone->getModel()::where('job_purchase_detail_id', $item->id)
                ->where('is_deleted', 0)->get();
            $diamond_detail = $this->model_job_purchase_detail_diamond->getModel()::where('job_purchase_detail_id', $item->id)
                ->where('is_deleted', 0)->get();
            $data[$index] = $item->toArray();
            $data[$index]['bead_detail'] = $bead_detail->toArray();
            $data[$index]['stone_detail'] = $stone_detail->toArray();
            $data[$index]['diamond_detail'] = $diamond_detail->toArray();
        }
        return $data;
    }
    public function singleJobPurchaseDetail($job_purchase_detail_id)
    {
        return $this->model_job_purchase_detail->getModel()::with('product')
            ->where('id', $job_purchase_detail_id)->where('is_deleted', 0)->first();
    }
    // post job purchase
    public function post($obj)
    {
        try {
            DB::beginTransaction();

            $journal_entry_id = null;
            $supplier_au_payment = null;
            foreach ($obj['job_purchase'] as $item) {
                $job_purchase = $this->model_job_purchase->getModel()::with('supplier_name')->find($item);
                $supplier = Supplier::find($job_purchase->supplier_id);

                $journal = Journal::find(config('enum.PV'));
                $job_purchase_date = date("Y-m-d", strtotime(str_replace('/', '-', $job_purchase->job_purchase_date)));
                // Add journal entry
                $data = [
                    "date" => $job_purchase_date,
                    "prefix" => $journal->prefix,
                    "journal_id" => $journal->id
                ];
                $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

                $journal_entry = new JournalEntry;
                $journal_entry->journal_id = $journal->id;
                $journal_entry->supplier_id = $job_purchase->supplier_id;
                $journal_entry->date_post = $job_purchase_date;
                $journal_entry->reference = 'Date :' . $job_purchase_date . ' Against ' . $job_purchase->job_purchase_no . '. From ' . $job_purchase->supplier_name->name;
                $journal_entry->entryNum = $entryNum;
                $journal_entry->createdby_id = Auth::User()->id;
                $journal_entry->save();

                $journal_entry_id = $journal_entry->id ?? null;

                if (
                    $supplier->account_id == null || $supplier->account_au_id == null || $supplier->account_dollar_id == null
                ) {
                    $message = "This Supplier/Karigar have  not 3 accounts. please update then post again.!";
                    return $message;
                }

                $purchase_account = Account::find($obj['purchase_account_id']);
                $supplir_account = Account::find($supplier->account_id);

                // Journal Entry Detail

                //PKR Job Purchase
                if ($job_purchase->total > 0) {
                    $PKR_Amount = str_replace(
                        ',',
                        '',
                        $job_purchase->total ?? 0
                    );
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase PKR Debit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // check no or 0
                        $job_purchase_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $PKR_Amount, //amount
                        $purchase_account->id, // account id
                        $purchase_account->code, // account code
                        Auth::User()->id //created by id
                    );
                    // PKR (Credit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase PKR Supplier/Karigar Credit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // check no or 0
                        $job_purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $PKR_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                //AU Job Purchase
                if ($job_purchase->total_au > 0) {
                    $AU_Amount = str_replace(
                        ',',
                        '',
                        $job_purchase->total_au ?? 0
                    );

                    // AU (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        1, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase Gold(AU) Debit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // check no or 0
                        $job_purchase_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $AU_Amount, //amount
                        $purchase_account->id, // account id
                        $purchase_account->code, // account code
                        Auth::User()->id //created by id
                    );

                    // AU (Credit)
                    $this->journal_entry_service->saveJVDetail(
                        1, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase Gold(AU) Supplier/Karigar Credit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // check no or 0
                        $job_purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $AU_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                //Dollar Job Purchase
                if ($job_purchase->total_dollar > 0) {
                    $Dollar_Amount = str_replace(',', '', $job_purchase->total_dollar ?? 0);

                    // AU (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        1, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase Dollar($) Debit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // job_purchase no or 0
                        $job_purchase_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $Dollar_Amount, //amount
                        $purchase_account->id, // account id
                        $purchase_account->code, // account code
                        Auth::User()->id //created by id
                    );

                    // Dollar (Credit)
                    $this->journal_entry_service->saveJVDetail(
                        1, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Job Purchase Dollar($) Supplier/Karigar Credit Entry', //explaination
                        $job_purchase->id, //bill no
                        0, // check no or 0
                        $job_purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $Dollar_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                // AU Recieved JV
                // if ($job_purchase->total_recieved_au > 0 && $obj['recieved_au_account_id'] != null) {
                //     $Paid_Amount = str_replace(',', '', $job_purchase->total_recieved_au ?? 0);

                //     $recieved_au_account = Account::find($obj['recieved_au_account_id']);
                //     $recieved_jv = $this->PaidAUtoSupplier($job_purchase->job_purchase_no, $job_purchase_date, $job_purchase->id, $supplier, $recieved_au_account, $supplir_account, $Paid_Amount);
                //     // Supplier PKR payment
                //     $supplier_au_payment = $this->supplier_payment_service->saveSupplierPaymentWithoutTax(
                //         $supplier->id,
                //         1,
                //         $obj['recieved_au_account_id'],
                //         $job_purchase_date,
                //         null,
                //         $Paid_Amount,
                //         $recieved_jv
                //     );
                // }

                //Job Purchase Update
                $job_purchase->is_posted = 1;
                $job_purchase->jv_id = $journal_entry_id;
                // $job_purchase->jv_recieved_id = ($recieved_jv != null) ? $recieved_jv->id : null;
                // $job_purchase->supplier_au_payment_id = ($supplier_au_payment != null) ? $supplier_au_payment->id : null;
                $job_purchase->update();

                //Purchase order update
                $purchase_order = $this->model_purchase_order->getModel()::find($job_purchase->purchase_order_id);
                $purchase_order->is_complete = 1;
                $purchase_order->update();
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // AU paid to supplier
    public function PaidAUtoSupplier($job_purchase_no, $job_purchase_date, $bill_no, $supplier, $paid_au_account, $supplir_au_account, $Paid_au_Amount)
    {
        $journal_type = ($paid_au_account->is_cash_account == 1) ? config('enum.CPV') : config('enum.BPV');
        $journal = Journal::find($journal_type);
        $data = ["date" => $job_purchase_date, "prefix" => $journal->prefix, "journal_id" => $journal->id];
        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry = new JournalEntry;
        $journal_entry->journal_id = $journal->id;
        $journal_entry->supplier_id = $supplier->id;
        $journal_entry->date_post = $job_purchase_date;
        $journal_entry->reference = 'Date :' . $job_purchase_date . ' AU payment Against RK. ' . $job_purchase_no;
        $journal_entry->entryNum = $entryNum;
        $journal_entry->createdby_id = Auth::User()->id;
        $journal_entry->save();

        $au_jv_id = $journal_entry->id;

        // Journal Entry Detail
        $Paid_au_Amount = str_replace(',', '', $Paid_au_Amount);

        // Journal entry detail (Debit)
        $this->journal_entry_service->saveJVDetail(
            1,
            $au_jv_id, // journal entry id
            'Paid AU Payment Debit Against Job Purchase. ' . $job_purchase_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $job_purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $Paid_au_Amount, //amount
            $supplir_au_account->id, // account id
            $supplir_au_account->code, // account code
            Auth::User()->id //created by id
        );

        // Journal entry detail (Credit)
        $this->journal_entry_service->saveJVDetail(
            1, // 0 for PKR, 1 for AU, 2 for Dollar
            $au_jv_id, // journal entry id
            'Paid AU Payment Credit Against Job Purchase. ' . $job_purchase_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $job_purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $Paid_au_Amount, //amount
            $paid_au_account->id, // account id
            $paid_au_account->code, // account code
            Auth::User()->id //created by id
        );

        return $journal_entry;
    }

    // delete by id
    public function unpost($job_purchase_id)
    {
        try {
            DB::beginTransaction();
            $job_purchase = $this->model_job_purchase->find($job_purchase_id);

            if ($job_purchase->jv_id != null) {
                $journal_entry = JournalEntry::find($job_purchase->jv_id);
                $journal_entry->is_deleted = 1;
                $journal_entry->deletedby_id = Auth::User()->id;
                $journal_entry->update();
            }

            if ($job_purchase->jv_au_id != null) {
                $jv_au = JournalEntry::find($job_purchase->jv_au_id);
                $jv_au->is_deleted = 1;
                $jv_au->deletedby_id = Auth::User()->id;
                $jv_au->update();
            }
            if ($job_purchase->jv_dollar_id != null) {
                $jv_dollar = JournalEntry::find($job_purchase->jv_dollar_id);
                $jv_dollar->is_deleted = 1;
                $jv_dollar->deletedby_id = Auth::User()->id;
                $jv_dollar->update();
            }

            if ($job_purchase->jv_recieved_id != null) {
                $jv_recieved = JournalEntry::find($job_purchase->jv_recieved_id);
                $jv_recieved->is_deleted = 1;
                $jv_recieved->deletedby_id = Auth::User()->id;
                $jv_recieved->update();
            }

            //payment delete
            if ($job_purchase->supplier_au_payment_id != null) {
                $supplier_au_payment = SupplierPayment::find($job_purchase->supplier_au_payment_id);
                $supplier_au_payment->is_deleted = 1;
                $supplier_au_payment->deletedby_id = Auth::User()->id;
                $supplier_au_payment->update();
            }
            $job_purchase->is_posted = 0;
            $job_purchase->jv_id = Null;
            $job_purchase->jv_au_id = Null;
            $job_purchase->jv_dollar_id = Null;
            $job_purchase->jv_recieved_id = Null;
            $job_purchase->supplier_au_payment_id = Null;
            $job_purchase->update();
            //Purchase order update
            $purchase_order = $this->model_purchase_order->getModel()::find($job_purchase->purchase_order_id);
            $purchase_order->is_complete = 0;
            $purchase_order->update();
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    public function deleteById($job_purchase_id)
    {
        try {
            DB::beginTransaction();

            $job_purchase = $this->model_job_purchase->getModel()::find($job_purchase_id);

            if ($job_purchase->jv_id != null) {
                $journal_entry = JournalEntry::find($job_purchase->jv_id);
                $journal_entry->is_deleted = 1;
                $journal_entry->deletedby_id = Auth::User()->id;
                $journal_entry->update();
            }

            if ($job_purchase->jv_au_id != null) {
                $jv_au = JournalEntry::find($job_purchase->jv_au_id);
                $jv_au->is_deleted = 1;
                $jv_au->deletedby_id = Auth::User()->id;
                $jv_au->update();
            }
            if ($job_purchase->jv_dollar_id != null) {
                $jv_dollar = JournalEntry::find($job_purchase->jv_dollar_id);
                $jv_dollar->is_deleted = 1;
                $jv_dollar->deletedby_id = Auth::User()->id;
                $jv_dollar->update();
            }

            if ($job_purchase->jv_recieved_id != null) {
                $jv_recieved = JournalEntry::find($job_purchase->jv_recieved_id);
                $jv_recieved->is_deleted = 1;
                $jv_recieved->deletedby_id = Auth::User()->id;
                $jv_recieved->update();
            }

            //payment delete
            if ($job_purchase->supplier_au_payment_id != null) {
                $supplier_au_payment = SupplierPayment::find($job_purchase->supplier_au_payment_id);
                $supplier_au_payment->is_deleted = 1;
                $supplier_au_payment->deletedby_id = Auth::User()->id;
                $supplier_au_payment->update();
            }

            // job purchase update
            $job_purchase->is_deleted = 1;
            $job_purchase->deletedby_id = Auth::user()->id;
            $job_purchase->is_posted = 0;
            $job_purchase->jv_id = Null;
            $job_purchase->jv_au_id = Null;
            $job_purchase->jv_dollar_id = Null;
            $job_purchase->jv_recieved_id = Null;
            $job_purchase->supplier_au_payment_id = Null;
            $job_purchase->update();

            //Purchase order update
            $purchase_order = $this->model_purchase_order->getModel()::find($job_purchase->purchase_order_id);
            $purchase_order->is_complete = 0;
            $purchase_order->update();
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    public function statusById($id)
    {
        $job_purchase = $this->model_job_purchase->getModel()::find($id);
        if ($job_purchase->is_active == 0) {
            $job_purchase->is_active = 1;
        } else {
            $job_purchase->is_active = 0;
        }
        $job_purchase->updatedby_id = Auth::user()->id;
        $job_purchase->update();

        if ($job_purchase)
            return true;

        return false;
    }

    // get by product id
    public function getJobPurchaseByProductId($product_id)
    {
        try {
            DB::beginTransaction();

            $job_purchase = $this->model_job_purchase->getModel()::join('job_purchase_details', 'job_purchase_details.job_purchase_id', 'job_purchases.id')
                ->select(
                    'job_purchases.id as job_purchase_id',
                    'job_purchases.job_purchase_no',
                    'job_purchase_details.id as job_purchase_detail_id'
                )
                ->where('job_purchase_details.product_id', $product_id)
                ->where('job_purchase_details.is_finish_product', 0)
                ->where('job_purchases.is_posted', 1)
                ->get();

            return $job_purchase;
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // Bead Detail
    public function jobPurchaseBeadDetail($job_purchase_detail_id, $product_id)
    {
        return $this->model_job_purchase_detail_bead->getModel()::with(['job_purchase_detail'])
            ->where('job_purchase_detail_id', $job_purchase_detail_id)
            // ->where('product_id', $product_id)
            ->where('is_deleted', 0)
            ->get();
    }

    // Stone Detail
    public function jobPurchaseStoneDetail($job_purchase_detail_id, $product_id)
    {
        return $this->model_job_purchase_detail_stone->getModel()::with(['job_purchase_detail'])
            ->where('job_purchase_detail_id', $job_purchase_detail_id)
            // ->where('product_id', $product_id)
            ->where('is_deleted', 0)
            ->get();
    }

    // Diamond Detail
    public function jobPurchaseDiamondDetail($job_purchase_detail_id, $product_id)
    {
        return $this->model_job_purchase_detail_diamond->getModel()::with(['job_purchase_detail'])
            ->where('job_purchase_detail_id', $job_purchase_detail_id)
            // ->where('product_id', $product_id)
            ->where('is_deleted', 0)
            ->get();
    }
}

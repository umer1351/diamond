<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\RattiKaat;
use App\Models\RattiKaatBead;
use App\Models\RattiKaatDetail;
use App\Models\RattiKaatDiamond;
use App\Models\RattiKaatStone;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Repository\Repository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RattiKaatService
{
    protected $model_ratti_kaat;
    protected $model_ratti_kaat_detail;
    protected $model_ratti_kaat_bead;
    protected $model_ratti_kaat_stone;
    protected $model_ratti_kaat_diamond;

    protected $common_service;
    protected $journal_entry_service;
    protected $supplier_payment_service;
    public function __construct()
    {
        // set the model
        $this->model_ratti_kaat = new Repository(new RattiKaat());
        $this->model_ratti_kaat_detail = new Repository(new RattiKaatDetail());
        $this->model_ratti_kaat_bead = new Repository(new RattiKaatBead());
        $this->model_ratti_kaat_stone = new Repository(new RattiKaatStone());
        $this->model_ratti_kaat_diamond = new Repository(new RattiKaatDiamond());

        $this->common_service = new CommonService();
        $this->journal_entry_service = new JournalEntryService();
        $this->supplier_payment_service = new SupplierPaymentService();
    }
    //Ratti Kaat
    public function getRattiKaatSource($obj)
    {
        $wh = [];
        if ($obj['posted'] != '') {
            $wh[] = ['is_posted', $obj['posted']];
        }
        if ($obj['supplier_id'] != '') {
            $wh[] = ['supplier_id', $obj['supplier_id']];
        }
        $model = RattiKaat::has('RattiKaatDetail')->with(['supplier_name', 'purchase_account_name', 'paid_account_name'])
            ->whereBetween('purchase_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
            ->where('is_deleted', 0)
            ->where($wh);
        $data = DataTables::of($model)
            ->addColumn('check_box', function ($item) {
                if ($item->is_posted != 1)
                    return '<input type="checkbox" class="sub_chk" value="' . $item->id . '" data-id="' . $item->id . '" >';
            })
            ->addColumn('supplier', function ($item) {
                return $item->supplier_name->name ?? '';
            })
            ->addColumn('purchase_account', function ($item) {
                $code = $item->purchase_account_name->code ?? '';
                $name = $item->purchase_account_name->name ?? '';
                return  $code . '-' . $name;
            })
            // ->addColumn('paid_account', function ($item) {
            //     return $item->paid_account_name->code ?? '' . '-' . $item->paid_account_name->name ?? '';
            // })
            ->addColumn('posted', function ($item) {
                $badge_color = $item->is_posted == 0 ? 'badge-danger' : 'badge-success';
                $badge_text = $item->is_posted == 0 ? 'Unposted' : 'Posted';
                return '<span class="badge ' . $badge_color . '">' . $badge_text . '</span>';
            })
            ->addColumn('action', function ($item) {

                $jvs = '';
                if ($item->jv_id != null)
                    $jvs .= "filter[]=" . $item->jv_id . "";

                if ($item->paid_jv_id != null)
                    $jvs .= "&filter[]=" . $item->paid_jv_id . "";


                if ($item->paid_au_jv_id != null)
                    $jvs .= "&filter[]=" . $item->paid_au_jv_id . "";

                if ($item->paid_dollar_jv_id != null)
                    $jvs .= "&filter[]=" . $item->paid_dollar_jv_id . "";

                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' href='ratti-kaats/edit/" . $item->id . "' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Edit' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $unpost_column    = "<a class='text-primary mr-2' id='unpost' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Unpost'><i title='Unpost' class='nav-icon mr-2 fa fa-refresh'></i>Unpost</a>";
                $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteRattiKaat' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                if (Auth::user()->can('ratti_kaat_edit') && $item->is_posted == 0)
                    $action_column .= $edit_column;
                if (Auth::user()->can('ratti_kaat_post') && $item->is_posted == 1)
                    $action_column .= $unpost_column;
                if (Auth::user()->can('ratti_kaat_jvs') && $item->is_posted == 1)
                    $action_column .= $all_print_column;
                if (Auth::user()->can('ratti_kaat_delete'))
                    $action_column .= $delete_column;


                return $action_column;
            })
            ->rawColumns(['check_box', 'supplier', 'purchase_account', 'posted', 'action'])
            ->addIndexColumn()
            ->make(true);
        return $data;
    }
    // save Ratti Kaat
    public function saveRattiKaat()
    {
        try {
            DB::beginTransaction();
            $obj = [
                'ratti_kaat_no' => $this->common_service->generateRattiKaatNo(),
                'createdby_id' => Auth::User()->id
            ];
            $saved_obj = $this->model_ratti_kaat->create($obj);

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return $saved_obj;
    }

    // update Ratti Kaat
    public function updateRattiKaat($obj)
    {
        try {
            DB::beginTransaction();
            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_ratti_kaat->update($obj, $obj['id']);
            $saved_obj = $this->model_ratti_kaat->find($obj['id']);

            $this->model_ratti_kaat_detail->getModel()::where('ratti_kaat_id', $obj['id'])
                ->update(['is_deleted' => 1, 'deletedby_id' => Auth::User()->id]);

            $total = 0;
            $total_au = 0;
            $total_dollar = 0;
            $purchaseDetail = json_decode($obj['purchaseDetail'], true);
            foreach ($purchaseDetail as $key => $item) {
                $total_au += $item['pure_payable'];
                $total_dollar += $item['total_dollar'];
                $total += $item['total_amount'];
                $detail = [
                    "ratti_kaat_id" => $obj['id'],
                    "product_id" => $item['product_id'],
                    "description" => $item['description'],
                    "scale_weight" => $item['scale_weight'],
                    "bead_weight" => $item['bead_weight'],
                    "stones_weight" => $item['stones_weight'],
                    "diamond_carat" => $item['diamond_carat'],
                    "net_weight" => $item['net_weight'],
                    "supplier_kaat" => $item['supplier_kaat'],
                    "kaat" => $item['kaat'],
                    "approved_by" => ($item['approved_by'] != '') ? $item['approved_by'] : null,
                    "pure_payable" => $item['pure_payable'],
                    "other_charge" => ($item['other_charge'] != '') ? $item['other_charge'] : 0,
                    "total_bead_amount" => ($item['total_bead_amount'] != '') ? $item['total_bead_amount'] : 0,
                    "total_stones_amount" => ($item['total_stones_amount'] != '') ? $item['total_stones_amount'] : 0,
                    "total_diamond_amount" => ($item['total_diamond_amount'] != '') ? $item['total_diamond_amount'] : 0,
                    "total_dollar" => ($item['total_dollar'] != '') ? $item['total_dollar'] : 0,
                    "total_amount" => ($item['total_amount'] != '') ? $item['total_amount'] : 0,
                    "createdby_id" => Auth::User()->id,
                    "created_at" => Carbon::now()
                ];
                $ratti_kaat_detail = $this->model_ratti_kaat_detail->getModel()::create($detail);

                // Beads
                $this->model_ratti_kaat_bead->getModel()::where('ratti_kaat_detail_id', $ratti_kaat_detail->id)
                ->update(['is_deleted' => 1, 'deletedby_id' => Auth::User()->id]);
                foreach ($item['beadData'] as $key => $bead) {
                    $objBead = [
                        "type"=>$bead['type']??'',
                        "beads"=>$bead['beads']??0,
                        "gram"=>$bead['gram']??0,
                        "carat"=>$bead['carat']??0,
                        "gram_rate"=>$bead['gram_rate']??0,
                        "carat_rate"=>$bead['carat_rate']??0,
                        "total_amount"=>$bead['total_amount']??0,
                        "ratti_kaat_detail_id"=>$ratti_kaat_detail->id,
                        "createdby_id"=>Auth::User()->id
                    ];
                    $this->model_ratti_kaat_bead->getModel()::create($objBead);
                }

                // Stones
                $this->model_ratti_kaat_stone->getModel()::where('ratti_kaat_detail_id', $ratti_kaat_detail->id)
                ->update(['is_deleted' => 1, 'deletedby_id' => Auth::User()->id]);
                foreach ($item['stoneData'] as $key => $stone) {
                    $objStone = [
                        "category"=>$stone['category']??'',
                        "type"=>$stone['type']??'',
                        "stones"=>$stone['stones']??0,
                        "gram"=>$stone['gram']??0,
                        "carat"=>$stone['carat']??0,
                        "gram_rate"=>$stone['gram_rate']??0,
                        "carat_rate"=>$stone['carat_rate']??0,
                        "total_amount"=>$stone['total_amount']??0,
                        "ratti_kaat_detail_id"=>$ratti_kaat_detail->id,
                        "createdby_id"=>Auth::User()->id
                    ];
                    $this->model_ratti_kaat_stone->getModel()::create($objStone);
                }

                // Diamonds
                $this->model_ratti_kaat_diamond->getModel()::where('ratti_kaat_detail_id', $ratti_kaat_detail->id)
                ->update(['is_deleted' => 1, 'deletedby_id' => Auth::User()->id]);
                foreach ($item['diamondData'] as $key => $diamond) {
                    $objDiamond = [
                        "type"=>$diamond['type']??'',
                        "cut"=>$diamond['cut']??'',
                        "color"=>$diamond['color']??'',
                        "clarity"=>$diamond['clarity']??'',
                        "diamonds"=>$diamond['diamonds']??0,
                        "carat"=>$diamond['carat']??0,
                        "carat_rate"=>$diamond['carat_rate']??0,
                        "total_amount"=>$diamond['total_amount']??0,
                        "total_dollar"=>$diamond['total_dollar']??0,
                        "ratti_kaat_detail_id"=>$ratti_kaat_detail->id,
                        "createdby_id"=>Auth::User()->id
                    ];
                    $this->model_ratti_kaat_diamond->getModel()::create($objDiamond);
                }
            }


            $this->model_ratti_kaat->update(['total_au' => $total_au, 'total_dollar' => $total_dollar, 'total' => $total], $obj['id']);
            if (!$saved_obj)
                return false;
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return $saved_obj;
    }

    // post Ratti Kaat
    public function postRattiKaat($obj)
    {
        try {
            DB::beginTransaction();

            $journal_entry_id = null;
            $paid_jv = null;
            $paid_au_jv = Null;
            $paid_dollar_jv = Null;
            $supplier_payment = null;
            $supplier_au_payment = null;
            $supplier_dollar_payment = null;

            $purchase_account_id = $obj['purchase_account'];
            $paid_account_id = $obj['paid_account'];
            $paid_account_au_id = $obj['paid_account_au'];
            $paid_account_dollar_id = $obj['paid_account_dollar'];

            foreach ($obj['ratti_kaat'] as $item) {
                $ratti_kaat = RattiKaat::with('supplier_name')->find($item);
                $supplier = Supplier::find($ratti_kaat->supplier_id);

                $journal = Journal::find(config('enum.PV'));
                $purchase_date = date("Y-m-d", strtotime(str_replace('/', '-', $ratti_kaat->purchase_date)));
                // Add journal entry
                $data = [
                    "date" => $purchase_date,
                    "prefix" => $journal->prefix,
                    "journal_id" => $journal->id
                ];
                $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

                $journal_entry = new JournalEntry;
                $journal_entry->journal_id = $journal->id;
                $journal_entry->supplier_id = $ratti_kaat->supplier_id;
                $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $ratti_kaat->purchase_date)));
                $journal_entry->reference = 'Date :' . $ratti_kaat->purchase_date . ' Against ' . $ratti_kaat->ratti_kaat_no . '. From ' . $ratti_kaat->supplier_name->name;
                $journal_entry->entryNum = $entryNum;
                $journal_entry->createdby_id = Auth::User()->id;
                $journal_entry->save();

                $journal_entry_id = $journal_entry->id ?? null;

                if ($supplier->account_id == null) {
                    $message = "This Supplier/Karigar have  not COA account. please update then post again.!";
                    return $message;
                }

                $purchase_account = Account::find($purchase_account_id);
                $supplir_account = Account::find($supplier->account_id);

                // Journal Entry Detail

                //PKR Ratti Kaat
                if ($ratti_kaat->total > 0) {
                    $PKR_Amount = str_replace(',', '', $ratti_kaat->total ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Ratti Kaat PKR Debit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
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
                        'Ratti Kaat PKR Supplier/Karigar Credit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $PKR_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                //AU Ratti Kaat
                if ($ratti_kaat->total_au > 0) {
                    $AU_Amount = str_replace(',', '', $ratti_kaat->total_au ?? 0);

                    // AU (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        1, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Ratti Kaat Gold(AU) Debit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
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
                        'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $AU_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                //Dollar Ratti Kaat
                if ($ratti_kaat->total_dollar > 0) {
                    $Dollar_Amount = str_replace(',', '', $ratti_kaat->total_dollar ?? 0);

                    // Dollar (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        2, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Ratti Kaat Dollar($) Debit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $Dollar_Amount, //amount
                        $purchase_account->id, // account id
                        $purchase_account->code, // account code
                        Auth::User()->id //created by id
                    );

                    // Dollar (Credit)
                    $this->journal_entry_service->saveJVDetail(
                        2, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Ratti Kaat Dollar($) Supplier/Karigar Credit Entry', //explaination
                        $ratti_kaat->id, //bill no
                        0, // check no or 0
                        $ratti_kaat->purchase_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $Dollar_Amount, //amount
                        $supplir_account->id, // account id
                        $supplir_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }


                if ($ratti_kaat->paid > 0 && $paid_account_id != null) {
                    $Paid_Amount = str_replace(',', '', $ratti_kaat->paid ?? 0);

                    $paid_account = Account::find($paid_account_id);
                    $paid_jv = $this->PaidtoSupplier($ratti_kaat->ratti_kaat_no, $ratti_kaat->purchase_date, $ratti_kaat->id, $supplier, $paid_account, $supplir_account, $Paid_Amount);
                    // Supplier PKR payment
                    $supplier_payment = $this->supplier_payment_service->saveSupplierPaymentWithoutTax(
                        $supplier->id,
                        0,
                        $paid_account_id,
                        $ratti_kaat->purchase_date,
                        null,
                        $Paid_Amount,
                        $paid_jv
                    );
                }

                // AU Payment JV
                if ($ratti_kaat->paid_au > 0  && $paid_account_au_id != null) {
                    $Paid_au_Amount = str_replace(',', '', $ratti_kaat->paid_au ?? 0);

                    $paid_au_account = Account::find($paid_account_au_id);
                    $paid_au_jv = $this->PaidAUtoSupplier($ratti_kaat->ratti_kaat_no, $ratti_kaat->purchase_date, $ratti_kaat->id, $supplier, $paid_au_account, $supplir_account, $Paid_au_Amount);

                    // Supplier AU payment
                    $supplier_au_payment = $this->supplier_payment_service->saveSupplierPaymentWithoutTax(
                        $supplier->id,
                        1,
                        $paid_account_au_id,
                        $ratti_kaat->purchase_date,
                        null,
                        $Paid_au_Amount,
                        $paid_au_jv
                    );
                }

                // Dollar Payment JV
                if ($ratti_kaat->paid_dollar > 0  && $paid_account_dollar_id != null) {
                    $Paid_dollar_Amount = str_replace(',', '', $ratti_kaat->paid_dollar ?? 0);

                    $paid_dollar_account = Account::find($paid_account_dollar_id);
                    $paid_dollar_jv = $this->PaidDollartoSupplier($ratti_kaat->ratti_kaat_no, $ratti_kaat->purchase_date, $ratti_kaat->id, $supplier, $paid_dollar_account, $supplir_account, $Paid_dollar_Amount);
                    // Supplier Dollar payment
                    $supplier_dollar_payment = $this->supplier_payment_service->saveSupplierPaymentWithoutTax(
                        $supplier->id,
                        2,
                        $paid_account_dollar_id,
                        $ratti_kaat->purchase_date,
                        null,
                        $Paid_dollar_Amount,
                        $paid_dollar_jv
                    );
                }

                // Purchase Update
                $ratti_kaat->purchase_account = $purchase_account_id;
                $ratti_kaat->paid_account = $paid_account_id;
                $ratti_kaat->paid_account_au = $paid_account_au_id;
                $ratti_kaat->paid_account_dollar = $paid_account_dollar_id;
                $ratti_kaat->is_posted = 1;
                $ratti_kaat->jv_id = $journal_entry_id;
                $ratti_kaat->paid_jv_id = ($paid_jv != null) ? $paid_jv->id : null;
                $ratti_kaat->paid_au_jv_id = ($paid_au_jv != null) ? $paid_au_jv->id : null;
                $ratti_kaat->paid_dollar_jv_id = ($paid_dollar_jv != null) ? $paid_dollar_jv->id : null;
                $ratti_kaat->supplier_payment_id = ($supplier_payment != null) ? $supplier_payment->id : null;
                $ratti_kaat->supplier_au_payment_id = ($supplier_au_payment != null) ? $supplier_au_payment->id : null;
                $ratti_kaat->supplier_dollar_payment_id = ($supplier_dollar_payment != null) ? $supplier_dollar_payment->id : null;
                $ratti_kaat->update();
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // PKR paid to supplier
    public function PaidtoSupplier($ratti_kaat_no, $purchase_date, $bill_no, $supplier, $paid_account, $supplir_account, $Paid_Amount)
    {
        $journal_type = ($paid_account->is_cash_account == 1) ? config('enum.CPV') : config('enum.BPV');
        $journal = Journal::find($journal_type);
        $data = ["date" => $purchase_date, "prefix" => $journal->prefix, "journal_id" => $journal->id];
        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry = new JournalEntry;
        $journal_entry->journal_id = $journal->id;
        $journal_entry->supplier_id = $supplier->id;
        $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $purchase_date)));
        $journal_entry->reference = 'Date :' . $purchase_date . ' PKR Payment Against RK. ' . $ratti_kaat_no;
        $journal_entry->entryNum = $entryNum;
        $journal_entry->createdby_id = Auth::User()->id;
        $journal_entry->save();

        $paid_jv_id = $journal_entry->id;

        // Journal Entry Detail
        $Paid_Amount = str_replace(',', '', $Paid_Amount);

        // Journal entry detail (Debit)
        $this->journal_entry_service->saveJVDetail(
            0,
            $paid_jv_id, // journal entry id
            'Paid PKR Payment Debit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $Paid_Amount, //amount
            $supplir_account->id, // account id
            $supplir_account->code, // account code
            Auth::User()->id //created by id
        );

        // Journal entry detail (Credit)
        $this->journal_entry_service->saveJVDetail(
            0, // 0 for PKR, 1 for AU, 2 for Dollar
            $paid_jv_id, // journal entry id
            'Paid PKR Payment Credit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $Paid_Amount, //amount
            $paid_account->id, // account id
            $paid_account->code, // account code
            Auth::User()->id //created by id
        );

        return $journal_entry;
    }

    // AU paid to supplier
    public function PaidAUtoSupplier($ratti_kaat_no, $purchase_date, $bill_no, $supplier, $paid_au_account, $supplir_account, $Paid_au_Amount)
    {
        $journal_type = ($paid_au_account->is_cash_account == 1) ? config('enum.CPV') : config('enum.BPV');
        $journal = Journal::find($journal_type);
        $data = ["date" => $purchase_date, "prefix" => $journal->prefix, "journal_id" => $journal->id];
        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry = new JournalEntry;
        $journal_entry->journal_id = $journal->id;
        $journal_entry->supplier_id = $supplier->id;
        $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $purchase_date)));
        $journal_entry->reference = 'Date :' . $purchase_date . ' AU payment Against RK. ' . $ratti_kaat_no;
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
            'Paid AU Payment Debit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $Paid_au_Amount, //amount
            $supplir_account->id, // account id
            $supplir_account->code, // account code
            Auth::User()->id //created by id
        );

        // Journal entry detail (Credit)
        $this->journal_entry_service->saveJVDetail(
            1, // 0 for PKR, 1 for AU, 2 for Dollar
            $au_jv_id, // journal entry id
            'Paid AU Payment Credit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $Paid_au_Amount, //amount
            $paid_au_account->id, // account id
            $paid_au_account->code, // account code
            Auth::User()->id //created by id
        );

        return $journal_entry;
    }

    // Dollar paid to supplier
    public function PaidDollartoSupplier($ratti_kaat_no, $purchase_date, $bill_no, $supplier, $paid_dollar_account, $supplir_account, $Paid_dollar_Amount)
    {
        $journal_type = ($paid_dollar_account->is_cash_account == 1) ? config('enum.CPV') : config('enum.BPV');
        $journal = Journal::find($journal_type);
        $data = ["date" => $purchase_date, "prefix" => $journal->prefix, "journal_id" => $journal->id];
        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

        $journal_entry = new JournalEntry;
        $journal_entry->journal_id = $journal->id;
        $journal_entry->supplier_id = $supplier->id;
        $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $purchase_date)));
        $journal_entry->reference = 'Date :' . $purchase_date . ' Dollar payment Against RK. ' . $ratti_kaat_no;
        $journal_entry->entryNum = $entryNum;
        $journal_entry->createdby_id = Auth::User()->id;
        $journal_entry->save();

        $dollar_jv_id = $journal_entry->id;

        // Journal Entry Detail
        $Paid_dollar_Amount = str_replace(',', '', $Paid_dollar_Amount);

        // Journal entry detail (Debit)
        $this->journal_entry_service->saveJVDetail(
            2,
            $dollar_jv_id, // journal entry id
            'Paid Dollar Payment Debit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            1, // is credit flag 0 for credit, 1 for debit
            $Paid_dollar_Amount, //amount
            $supplir_account->id, // account id
            $supplir_account->code, // account code
            Auth::User()->id //created by id
        );

        // Journal entry detail (Credit)
        $this->journal_entry_service->saveJVDetail(
            2, // 0 for PKR, 1 for dollar, 2 for Dollar
            $dollar_jv_id, // journal entry id
            'Paid Dollar Payment Credit Against Ratti Kaat. ' . $ratti_kaat_no, //explaination
            $bill_no, //bill no
            0, // check no or 0
            $purchase_date, //check date
            0, // is credit flag 0 for credit, 1 for debit
            $Paid_dollar_Amount, //amount
            $paid_dollar_account->id, // account id
            $paid_dollar_account->code, // account code
            Auth::User()->id //created by id
        );

        return $journal_entry;
    }

    // get by id
    public function getRattiKaatById($id)
    {
        $ratti_kaat = $this->model_ratti_kaat->getModel()::find($id);

        if (!$ratti_kaat)
            return false;

        return $ratti_kaat;
    }

    //ratti kaat detail 
    public function getRattiKaatDetail($ratti_kaat_id)
    {
        $ratti_kaat_detail = $this->model_ratti_kaat_detail->getModel()::with('product_name')->where('ratti_kaat_id', $ratti_kaat_id)->orderBy('id', 'DESC')->where('is_deleted', 0)->get();
        $data=[];
        foreach($ratti_kaat_detail as $item){
            $item['beadData']=$this->model_ratti_kaat_bead->getModel()::where('ratti_kaat_detail_id',$item->id)->where('is_deleted', 0)->get();
            $item['stoneData']=$this->model_ratti_kaat_stone->getModel()::where('ratti_kaat_detail_id',$item->id)->where('is_deleted', 0)->get();
            $item['diamondData']=$this->model_ratti_kaat_diamond->getModel()::where('ratti_kaat_detail_id',$item->id)->where('is_deleted', 0)->get();
            $data[]=$item;
        }
        return $data;
    }

    // status by id
    public function statusById($id)
    {
        $ratti_kaat = $this->model_ratti_kaat->getModel()::find($id);
        if ($ratti_kaat->is_active == 0) {
            $ratti_kaat->is_active = 1;
        } else {
            $ratti_kaat->is_active = 0;
        }
        $ratti_kaat->updatedby_id = Auth::user()->id;
        $ratti_kaat->update();

        if ($ratti_kaat)
            return true;

        return false;
    }
    // unpost by id
    public function unpostRattiKaatById($id)
    {
        try {
            DB::beginTransaction();
            $ratti_kaat = $this->model_ratti_kaat->find($id);
            $ratti_kaat->is_posted = 0;
            $ratti_kaat->updatedby_id = Auth::User()->id;
            $ratti_kaat->update();

            if ($ratti_kaat->jv_id != null) {
                $journal_entry = JournalEntry::find($ratti_kaat->jv_id);
                $journal_entry->is_deleted = 1;
                $journal_entry->deletedby_id = Auth::User()->id;
                $journal_entry->update();
            }

            if ($ratti_kaat->paid_jv_id != null) {
                $paid_jv = JournalEntry::find($ratti_kaat->paid_jv_id);
                $paid_jv->is_deleted = 1;
                $paid_jv->deletedby_id = Auth::User()->id;
                $paid_jv->update();
            }
            if ($ratti_kaat->paid_au_jv_id != null) {
                $paid_au_jv = JournalEntry::find($ratti_kaat->paid_au_jv_id);
                $paid_au_jv->is_deleted = 1;
                $paid_au_jv->deletedby_id = Auth::User()->id;
                $paid_au_jv->update();
            }
            if ($ratti_kaat->paid_dollar_jv_id != null) {
                $paid_dollar_jv = JournalEntry::find($ratti_kaat->paid_dollar_jv_id);
                $paid_dollar_jv->is_deleted = 1;
                $paid_dollar_jv->deletedby_id = Auth::User()->id;
                $paid_dollar_jv->update();
            }

            //payment delete
            if ($ratti_kaat->supplier_payment_id != null) {
                $supplier_payment = SupplierPayment::find($ratti_kaat->supplier_payment_id);
                $supplier_payment->is_deleted = 1;
                $supplier_payment->deletedby_id = Auth::User()->id;
                $supplier_payment->update();
            }
            if ($ratti_kaat->supplier_au_payment_id != null) {
                $supplier_au_payment = SupplierPayment::find($ratti_kaat->supplier_au_payment_id);
                $supplier_au_payment->is_deleted = 1;
                $supplier_au_payment->deletedby_id = Auth::User()->id;
                $supplier_au_payment->update();
            }
            if ($ratti_kaat->supplier_dollar_payment_id != null) {
                $supplier_dollar_payment = SupplierPayment::find($ratti_kaat->supplier_dollar_payment_id);
                $supplier_dollar_payment->is_deleted = 1;
                $supplier_dollar_payment->deletedby_id = Auth::User()->id;
                $supplier_dollar_payment->update();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
    // delete by id
    public function deleteRattiKaatById($id)
    {
        try {
            DB::beginTransaction();
            $ratti_kaat = $this->model_ratti_kaat->find($id);
            $ratti_kaat->is_deleted = 1;
            $ratti_kaat->deletedby_id = Auth::User()->id;
            $ratti_kaat->update();

            if ($ratti_kaat->jv_id != null) {
                $journal_entry = JournalEntry::find($ratti_kaat->jv_id);
                $journal_entry->is_deleted = 1;
                $journal_entry->deletedby_id = Auth::User()->id;
                $journal_entry->update();
            }

            if ($ratti_kaat->paid_jv_id != null) {
                $paid_jv = JournalEntry::find($ratti_kaat->paid_jv_id);
                $paid_jv->is_deleted = 1;
                $paid_jv->deletedby_id = Auth::User()->id;
                $paid_jv->update();
            }
            if ($ratti_kaat->paid_au_jv_id != null) {
                $paid_au_jv = JournalEntry::find($ratti_kaat->paid_au_jv_id);
                $paid_au_jv->is_deleted = 1;
                $paid_au_jv->deletedby_id = Auth::User()->id;
                $paid_au_jv->update();
            }
            if ($ratti_kaat->paid_dollar_jv_id != null) {
                $paid_dollar_jv = JournalEntry::find($ratti_kaat->paid_dollar_jv_id);
                $paid_dollar_jv->is_deleted = 1;
                $paid_dollar_jv->deletedby_id = Auth::User()->id;
                $paid_dollar_jv->update();
            }

            //payment delete
            if ($ratti_kaat->supplier_payment_id != null) {
                $supplier_payment = SupplierPayment::find($ratti_kaat->supplier_payment_id);
                $supplier_payment->is_deleted = 1;
                $supplier_payment->deletedby_id = Auth::User()->id;
                $supplier_payment->update();
            }
            if ($ratti_kaat->supplier_au_payment_id != null) {
                $supplier_au_payment = SupplierPayment::find($ratti_kaat->supplier_au_payment_id);
                $supplier_au_payment->is_deleted = 1;
                $supplier_au_payment->deletedby_id = Auth::User()->id;
                $supplier_au_payment->update();
            }
            if ($ratti_kaat->supplier_dollar_payment_id != null) {
                $supplier_dollar_payment = SupplierPayment::find($ratti_kaat->supplier_dollar_payment_id);
                $supplier_dollar_payment->is_deleted = 1;
                $supplier_dollar_payment->deletedby_id = Auth::User()->id;
                $supplier_dollar_payment->update();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // get by product id
    public function getRattiKaatByProductId($product_id)
    {
        try {
            DB::beginTransaction();

            $ratti_kaat = $this->model_ratti_kaat->getModel()::join('ratti_kaat_details', 'ratti_kaat_details.ratti_kaat_id', 'ratti_kaats.id')
                ->join('products', 'ratti_kaat_details.product_id', 'products.id')
                ->select(
                    'ratti_kaats.id as ratti_kaat_id',
                    'ratti_kaats.ratti_kaat_no',
                    'ratti_kaat_details.id as ratti_kaat_detail_id',
                    'products.prefix'
                )
                ->where('ratti_kaat_details.product_id', $product_id)
                ->where('ratti_kaat_details.is_finish_product', 0)
                ->where('ratti_kaats.is_posted', 1)
                ->where('ratti_kaat_details.is_deleted', 0)
                ->get();

            return $ratti_kaat;
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // get ratti kaat detail by id
    public function getRattiKaatDetailById($detail_id)
    {
        try {
            DB::beginTransaction();

            $ratti_kaat_detail = $this->model_ratti_kaat_detail->find($detail_id);
            return $ratti_kaat_detail;

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    // get beads weight
    public function getBeadWeight($ratti_kaat_id)
    {
        return $this->model_ratti_kaat_bead->getModel()::with(['ratti_kaat_name', 'product_name'])
            ->where('ratti_kaat_detail_id', $ratti_kaat_id)
            ->where('is_deleted', 0)
            ->get();
    }
    public function saveBeadWeight($obj)
    {
        try {
            DB::beginTransaction();
            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_ratti_kaat_bead->create($obj);

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }

        return $saved_obj;
    }

    // delete bead by id
    public function deleteBeadWeightById($id)
    {
        $ratti_kaat_bead = $this->model_ratti_kaat_bead->getModel()::find($id);
        $ratti_kaat_bead->is_deleted = 1;
        $ratti_kaat_bead->deletedby_id = Auth::user()->id;
        $ratti_kaat_bead->update();

        if (!$ratti_kaat_bead)
            return false;

        return $ratti_kaat_bead;
    }

    // get stones weight
    public function getStoneWeight($ratti_kaat_detail_id)
    {
        return $this->model_ratti_kaat_stone->getModel()::with(['ratti_kaat_name', 'product_name'])
            ->where('ratti_kaat_detail_id', $ratti_kaat_detail_id)
            ->where('is_deleted', 0)
            ->get();
    }
    public function saveStoneWeight($obj)
    {
        try {
            DB::beginTransaction();
            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_ratti_kaat_stone->create($obj);

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }

        return $saved_obj;
    }

    // delete stone by id
    public function deleteStoneWeightById($id)
    {
        $ratti_kaat_stone = $this->model_ratti_kaat_stone->getModel()::find($id);
        $ratti_kaat_stone->is_deleted = 1;
        $ratti_kaat_stone->deletedby_id = Auth::user()->id;
        $ratti_kaat_stone->update();

        if (!$ratti_kaat_stone)
            return false;

        return $ratti_kaat_stone;
    }


    // get diamonds Carat
    public function getDiamondCarat($ratti_kaat_detail_id)
    {
        return $this->model_ratti_kaat_diamond->getModel()::with(['ratti_kaat_name', 'product_name'])
            ->where('ratti_kaat_detail_id', $ratti_kaat_detail_id)
            ->where('is_deleted', 0)
            ->get();
    }
    // public function saveDiamondCarat($obj)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $obj['createdby_id'] = Auth::User()->id;

    //         $saved_obj = $this->model_ratti_kaat_diamond->create($obj);

    //         DB::commit();
    //     } catch (Exception $e) {

    //         DB::rollback();
    //         throw $e;
    //     }

    //     return $saved_obj;
    // }

    // delete diamond by id
    public function deleteDiamondCaratById($id)
    {
        $ratti_kaat_diamond = $this->model_ratti_kaat_diamond->getModel()::find($id);
        $ratti_kaat_diamond->is_deleted = 1;
        $ratti_kaat_diamond->deletedby_id = Auth::user()->id;
        $ratti_kaat_diamond->update();

        if (!$ratti_kaat_diamond)
            return false;

        return $ratti_kaat_diamond;
    }
}

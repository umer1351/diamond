<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Repository\Repository;
use App\Models\OtherSale;
use App\Models\OtherSaleDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OtherSaleService
{
    // initialize protected model variables
    protected $model_other_sale;
    protected $model_other_sale_detail;
    protected $model_journal_entry;

    protected $common_service;
    protected $journal_entry_service;
    public function __construct()
    {
        // set the model
        $this->model_other_sale = new Repository(new OtherSale);
        $this->model_other_sale_detail = new Repository(new OtherSaleDetail);
        $this->model_journal_entry = new Repository(new JournalEntry);

        $this->common_service = new CommonService();
        $this->journal_entry_service = new JournalEntryService();
    }

    public function getSource($obj)
    {
        $wh = [];
        if ($obj['posted'] != '') {
            $wh[] = ['posted', $obj['posted']];
        }
        if ($obj['customer_id'] != '') {
            $wh[] = ['customer_id', $obj['customer_id']];
        }
        $model = $this->model_other_sale->getModel()::has('OtherSaleDetail')->where('is_deleted', 0)
            ->whereBetween('other_sale_date', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
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
            ->addColumn('action', function ($item) {

                $jvs = '';
                if ($item->jv_id != null)
                    $jvs .= "filter[]=" . $item->jv_id . "";

                $action_column = '';
                $unpost = '<a class="text text-danger" id="unpost_other_sale" data-toggle="tooltip" data-id="' . $item->id . '" data-original-title="Unpost" href="javascript:void(0)"><i class="fa fa-repeat"></i>Unpost</a>';
                $print_column    = "<a class='text-info mr-2' href='other-sale/print/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteOtherSale' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";


                if (Auth::user()->can('other_sale_print'))
                    $action_column .= $print_column;
                if (Auth::user()->can('other_sale_unpost') && $item->posted == 1)
                    $action_column .= $unpost;
                if (Auth::user()->can('other_sale_jvs') && $item->posted == 1)
                    $action_column .= $all_print_column;

                if (Auth::user()->can('other_sale_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['check_box', 'posted', 'action'])
            ->addIndexColumn()
            ->make(true);
        return $data;
    }

    public function getAllOtherSale()
    {
        return $this->model_other_sale->getModel()::with('customer_name')
            ->where('is_deleted', 0)
            ->get();
    }
    public function saveOtherSale()
    {
        try {
            DB::beginTransaction();
            $obj = [
                'other_sale_no' => $this->common_service->generateOtherSaleNo(),
                'createdby_id' => Auth::User()->id
            ];
            $saved_obj = $this->model_other_sale->create($obj);

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
            $customer = Customer::find($obj['customer_id']);
            $otherSaleDetail = json_decode($obj['otherProductDetail']);
            $saleObj = [
                "other_sale_date" => $obj['other_sale_date'],
                "customer_id" => $obj['customer_id'],
                "customer_name" => $customer->name ?? '',
                "customer_cnic" => $customer->cnic ?? '',
                "customer_contact" => $customer->contact ?? '',
                "customer_address" => $customer->address ?? '',
                "total_qty" => $obj['total_qty'] ?? 0,
                "warehouse_id" => $obj['warehouse_id'] ?? null,
                "total" => $obj['total'],
                "cash_amount" => $obj['cash_amount'] ?? 0,
                "bank_transfer_amount" => $obj['bank_transfer_amount'] ?? 0,
                "card_amount" => $obj['card_amount'] ?? 0,
                "advance_amount" => $obj['advance_amount'] ?? 0,
                "total_received" => $obj['cash_amount'] + $obj['bank_transfer_amount'] + $obj['card_amount'] + $obj['advance_amount'],
                "updatedby_id" => Auth::user()->id
            ];
            $sale = $this->model_other_sale->update($saleObj, $obj['id']);

            foreach ($otherSaleDetail as $item) {
                $otherSaleDetailObj = [
                    "other_sale_id" => $obj['id'],
                    "other_product_id" => $item->other_product_id ?? '',
                    "qty" => $item->qty??0.000,
                    "unit_price" => $item->unit_price??0.000,
                    "total_amount" => $item->total_amount,
                    "createdby_id" => Auth::user()->id
                ];
                $sale_detail = $this->model_other_sale_detail->create($otherSaleDetailObj);

            }

            DB::commit();
        } catch (Exception $e) {
            return $e;
        }

        return true;
    }

    public function getById($id)
    {
        return $this->model_other_sale->getModel()::with(['customer_name','other_product'])->find($id);
    }

    public function otherSaleDetail($other_sale_id)
    {
        $other_sale_detail = $this->model_other_sale_detail->getModel()::with([
            'other_product'
        ])
            ->where('other_sale_id', $other_sale_id)
            ->where('is_deleted', 0)->get();

        $data = [];
        foreach ($other_sale_detail as $item) {
            $data[] = [
                "code" => $item->other_product->code ?? '',
                "product" => $item->other_product->name ?? '',
                "unit" => $item->other_product->other_product_unit->name ?? '',
                "unit_price" => $item->unit_price??0,
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

            $journal_entry_id = null;
            $jv_id = null;
            foreach ($obj['other_sale'] as $item) {
                $other_sale = OtherSale::with('customer_name')->find($item);
                $customer = Customer::find($other_sale->customer_id);

                $journal = Journal::find(config('enum.SV'));
                $other_sale_date = date("Y-m-d", strtotime(str_replace('/', '-', $other_sale->other_sale_date)));
                // Add journal entry
                $data = [
                    "date" => $other_sale_date,
                    "prefix" => $journal->prefix,
                    "journal_id" => $journal->id
                ];
                $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

                $journal_entry = new JournalEntry;
                $journal_entry->journal_id = $journal->id;
                $journal_entry->customer_id = $other_sale->customer_id;
                $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $other_sale->other_sale_date)));
                $journal_entry->reference = 'Date :' . $other_sale->other_sale_date . ' other Sale ' . $other_sale->other_sale_no . '. Customer is ' . $other_sale->customer_name;
                $journal_entry->entryNum = $entryNum;
                $journal_entry->createdby_id = Auth::User()->id;
                $journal_entry->save();

                $journal_entry_id = $journal_entry->id ?? null;

                // cash amount
                if ($other_sale->cash_amount > 0) {
                    if ($obj['cash_account_id'] == null || $obj['cash_account_id'] == '') {
                        $msg = 'Cash Account not select but cash amount is greater then 0';
                        return $msg;
                    }
                    $cash_account = Account::find($obj['cash_account_id']);
                    $Cash_Amount = str_replace(',', '', $other_sale->cash_amount ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Cash Amount From Sale Debit Entry', //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $Cash_Amount, //amount
                        $cash_account->id, // account id
                        $cash_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                // Bank Transfer
                if ($other_sale->bank_transfer_amount > 0) {
                    if ($obj['bank_transfer_account_id'] == null || $obj['bank_transfer_account_id'] == '') {
                        $msg = 'Bank Transfer Account not select but bank transfer amount is greater then 0';
                        return $msg;
                    }
                    $bank_transfer_account = Account::find($obj['bank_transfer_account_id']);
                    $bank_transfer_Amount = str_replace(',', '', $other_sale->bank_transfer_amount ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Bank Amount Transfer From Sale Debit Entry', //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $bank_transfer_Amount, //amount
                        $bank_transfer_account->id, // account id
                        $bank_transfer_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                // Card Amount
                if ($other_sale->card_amount > 0) {
                    if ($obj['card_account_id'] == null || $obj['card_account_id'] == '') {
                        $msg = 'Card Account not select but card amount is greater then 0';
                        return $msg;
                    }
                    $card_account = Account::find($obj['card_account_id']);
                    $card_amount = str_replace(',', '', $other_sale->card_amount ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Card Amount From Sale Debit Entry', //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $card_amount, //amount
                        $card_account->id, // account id
                        $card_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                // advance Amount
                if ($other_sale->advance_amount > 0) {
                    if ($obj['advance_account_id'] == null || $obj['advance_account_id'] == '') {
                        $msg = 'Advance Account not select but advance amount is greater then 0';
                        return $msg;
                    }
                    $advance_account = Account::find($obj['advance_account_id']);
                    $advance_amount = str_replace(',', '', $other_sale->advance_amount ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Advance Amount From Sale Debit Entry', //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $advance_amount, //amount
                        $advance_account->id, // account id
                        $advance_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }


                $credit = $other_sale->total - $other_sale->total_received;
                // credit Amount
                if ($credit > 0) {
                    if ($customer->account_id == null || $customer->account_id == '') {
                        $msg = 'Customer Account has no account but paid amount is less then total amount';
                        return $msg;
                    }
                    $customer_account = Account::find($customer->account_id);
                    $credit_amount = str_replace(',', '', $credit ?? 0);
                    // PKR (Debit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Credit Amount From Sale Debit Entry from ' . $other_sale->customer_name, //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        1, // is credit flag 0 for credit, 1 for debit
                        $credit_amount, //amount
                        $customer_account->id, // account id
                        $customer_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }

                // revenue Amount
                if ($other_sale->total > 0) {
                    if ($obj['revenue_account_id'] == null || $obj['revenue_account_id'] == '') {
                        $msg = 'Revenue Account not select!';
                        return $msg;
                    }
                    $revenue_account = Account::find($obj['revenue_account_id']);
                    $revenue_amount = str_replace(',', '', $other_sale->total ?? 0);
                    // PKR (Credit)
                    $this->journal_entry_service->saveJVDetail(
                        0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                        $journal_entry_id, // journal entry id
                        'Revenue From Sale Credit Entry', //explaination
                        $other_sale->id, //bill no
                        0, // check no or 0
                        $other_sale->other_sale_date, //check date
                        0, // is credit flag 0 for credit, 1 for debit
                        $revenue_amount, //amount
                        $revenue_account->id, // account id
                        $revenue_account->code, // account code
                        Auth::User()->id //created by id
                    );
                }
                $other_sale->posted = 1;
                $other_sale->jv_id = $journal_entry_id;
                $other_sale->update();
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
    public function unpost($other_sale_id)
    {
        try {
            DB::beginTransaction();

            $other_sale = $this->model_other_sale->getModel()::find($other_sale_id);

            // Journal entry delete
            $journal_entry = $this->model_journal_entry->getModel()::find($other_sale->jv_id);
            $journal_entry->is_deleted = 1;
            $journal_entry->deletedby_id = Auth::user()->id;
            $journal_entry->update();

            // sale update
            $other_sale->posted = 0;
            $other_sale->jv_id = Null;
            $other_sale->update();

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    public function deleteById($other_sale_id)
    {
        try {
            DB::beginTransaction();

            $other_sale = $this->model_other_sale->getModel()::find($other_sale_id);

            if ($other_sale->jv_id != null && $other_sale->jv_id != '') {
                // Journal entry delete
                $journal_entry = $this->model_journal_entry->getModel()::find($other_sale->jv_id);
                $journal_entry->is_deleted = 1;
                $journal_entry->deletedby_id = Auth::user()->id;
                $journal_entry->update();
            }

            // sale update
            $other_sale->is_deleted = 1;
            $other_sale->deletedby_id = Auth::user()->id;
            $other_sale->posted = 0;
            $other_sale->jv_id = Null;
            $other_sale->update();

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }

    public function statusById($id)
    {
        $other_sale = $this->model_other_sale->getModel()::find($id);
        if ($other_sale->is_active == 0) {
            $other_sale->is_active = 1;
        } else {
            $other_sale->is_active = 0;
        }
        $other_sale->updatedby_id = Auth::user()->id;
        $other_sale->update();

        if ($other_sale)
            return true;

        return false;
    }

}

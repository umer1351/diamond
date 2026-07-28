<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Customer;
use App\Models\CustomerPayment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerPaymentService
{
    protected $model_customer_payment;
    protected $journal_entry_service;

    public function __construct()
    {
        //model
        $this->model_customer_payment = new CustomerPayment();
        $this->journal_entry_service = new JournalEntryService();
    }
    // Customer Payment
    public function getCustomerPaymentSource($obj)
    {
        $wh = [];
        if ($obj['start_date'] != '' && $obj['end_date'] != '') {
            $wh[] = ['payment_date', '>=', $obj['start_date']];
            $wh[] = ['payment_date', '<=', $obj['end_date']];
        }
        if ($obj['customer_id'] != '' && $obj['customer_id'] != 0) {
            $wh[] = ['customer_id', $obj['customer_id']];
        }
        $model = $this->model_customer_payment->getModel()::with('customer_name')
            ->where('is_deleted', 0)
            ->where($wh)
            ->orderBy('payment_date', 'DESC');

        $data = DataTables::of($model)
            ->addColumn('customer', function ($item) {
                $name = $item->customer_name->name ?? '';
                return $name;
            })
            ->addColumn('currency', function ($item) {
                if ($item->currency == 0) {
                    $name = 'PKR';
                } elseif ($item->currency == 0) {
                    $name = 'AU';
                } else {
                    $name = 'Dollar';
                }
                return $name;
            })
            ->addColumn('date', function ($item) {
                return date("d-m-Y", strtotime(str_replace('/', '-', $item->payment_date)));
            })
            ->addColumn('action', function ($item) {
                $jvs = '';
                if ($item->jv_id != null)
                    $jvs .= "filter[]=" . $item->jv_id . "";

                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' id='editCustomerPayment' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Edit' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $view_column    = "<a class='text-warning mr-2' id='viewCustomerPayment' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='View'><i title='View' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column    = "<a class='text-danger mr-2' id='DeleteCustomerPayment' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                // if (Auth::user()->can('customer_payment_edit')) {
                //     $action_column .= $edit_column;
                // }
                if (Auth::user()->can('customer_payment_view')) {

                    $action_column .= $view_column;
                }
                if (Auth::user()->can('customer_payment_delete')) {
                    $action_column .= $delete_column;
                }
                if (Auth::user()->can('customer_payment_jvs')) {
                    $action_column .= $all_print_column;
                }

                return $action_column;
            })
            ->rawColumns(['customer', 'currency', 'date', 'action'])
            ->make(true);
        return $data;
    }

    // save customer payment
    public function saveCustomerPayment($obj, $id)
    {
        try {
            DB::beginTransaction();
            $journal_entry_id = null;
            $customer = Customer::find($obj['customer_id']);
            $customer_account = Account::find($customer->account_id);
            $account = Account::find($obj['account_id']);
            $journal_type = ($account->account_id == 1) ? config('enum.CPV') : config('enum.BPV');
            $Amount = str_replace(',', '', $obj['sub_total']);
            $obj['createdby_id'] = Auth::user()->id;
            $saved_obj = $this->model_customer_payment->create($obj);

            $journal = Journal::find($journal_type);
            // Add journal entry
            $data = [
                "date" => $obj['payment_date'],
                "prefix" => $journal->prefix,
                "journal_id" => $journal->id
            ];
            $entryNum = $this->journal_entry_service->GenerateJournalEntryNum($data);

            $journal_entry = new JournalEntry;
            $journal_entry->journal_id = $journal->id;
            $journal_entry->customer_id = $obj['customer_id'];
            $journal_entry->sale_order_id = isset($obj['sale_order_id'])?$obj['sale_order_id']:null;
            $journal_entry->sale_id = isset($obj['sale_id'])?$obj['sale_id']:null;
            $journal_entry->date_post = date("Y-m-d", strtotime(str_replace('/', '-', $obj['payment_date'])));
            $journal_entry->reference = 'Date :' . $obj['payment_date'] . ' Against Customer. ' . $customer->name ?? '';
            $journal_entry->entryNum = $entryNum;
            $journal_entry->createdby_id = Auth::User()->id;
            $journal_entry->save();
            $journal_entry_id = $journal_entry->id;


            // Journal Entry Detail
            $Amount = str_replace(',', '', $obj['sub_total']);
            // Journal entry detail (Credit)
            $this->journal_entry_service->saveJVDetail(
                0,
                $journal_entry->id, // journal entry id
                'Customer Payment Credit To ' . $customer_account->name, //explaination
                $saved_obj->id, //bill no
                0, // check no or 0
                $obj['payment_date'], //check date
                0, // is credit flag 0 for credit, 1 for debit
                $Amount, //amount
                $customer_account->id, // account id
                $customer_account->code, // account code
                Auth::User()->id //created by id
            );
            // Journal entry detail (Debit)
            $this->journal_entry_service->saveJVDetail(
                0,
                $journal_entry->id, // journal entry id
                'Customer Payment Debit To '.$account->name, //explaination
                $saved_obj->id, //bill no
                0, // check no or 0
                $obj['payment_date'], //check date
                1, // is credit flag 0 for credit, 1 for debit
                $Amount, //amount
                $account->id, // account id
                $account->code, // account code
                Auth::User()->id //created by id
            );

            if ($obj['tax_amount'] > 0) {
                $tax_account = Account::find($obj['tax_account_id']);
                $TaxAmount = str_replace(',', '', $obj['tax_amount']);
                // Journal entry detail (Credit)
                $this->journal_entry_service->saveJVDetail(
                    0,
                    $journal_entry->id, // journal entry id
                    'Customer Tax Credit ', //explaination
                    $saved_obj->id, //bill no
                    0, // check no or 0
                    $obj['payment_date'], //check date
                    0, // is credit flag 0 for credit, 1 for debit
                    $TaxAmount, //amount
                    $account->id, // account id
                    $account->code, // account code
                    Auth::User()->id //created by id
                );

                // Journal entry detail (Debit)
                $this->journal_entry_service->saveJVDetail(
                    0,
                    $journal_entry->id, // journal entry id
                    'Customer Tax Debit ', //explaination
                    $saved_obj->id, //bill no
                    0, // check no or 0
                    $obj['payment_date'], //check date
                    1, // is credit flag 0 for credit, 1 for debit
                    $TaxAmount, //amount
                    $tax_account->id, // account id
                    $tax_account->code, // account code
                    Auth::User()->id //created by id
                );
            }
            $vendor_payment_update = CustomerPayment::find($saved_obj->id);
            $vendor_payment_update->jv_id = $journal_entry_id;
            $vendor_payment_update->update();


            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return $saved_obj;
    }

    public function saveCustomerPaymentWithoutTax($customer_id,$currency,$account_id,$payment_date,$reference,$amount,$jv_id)
    {

        $obj = [
            'customer_id' => $customer_id,
            'currency' => $currency,
            'account_id' => $account_id ?? null,
            'payment_date' => $payment_date,
            'reference' => $reference??null,
            'sub_total' => $amount,
            'total' => $amount,
            'tax' => 0.000,
            'tax_amount' => 0.000,
            'tax_account_id' => null,
            'jv_id'=>$jv_id->id
        ];

        $saved_obj = $this->model_customer_payment->create($obj);

        return $saved_obj;
    }
    // get customer payment by id
    public function getCustomerPaymentById($id)
    {
        $customer = $this->model_customer_payment->getModel()::find($id);

        if (!$customer)
            return false;

        return $customer;
    }

    public function deleteCustomerPaymentById($id)
    {
        try {
            DB::beginTransaction();
            $customer_payment = $this->model_customer_payment->find($id);
            $customer_payment->is_deleted = 1;
            $customer_payment->deletedby_id = Auth::User()->id;
            $customer_payment->update();

            $journal_entry = JournalEntry::find($customer_payment->jv_id);
            $journal_entry->is_deleted = 1;
            $journal_entry->deletedby_id = Auth::User()->id;
            $journal_entry->update();

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }
        return true;
    }
}

<?php

namespace App\Services\Concrete;

use App\Models\Account;
use App\Models\Customer;
use App\Models\GoldImpurityPurchase;
use App\Models\GoldImpurityPurchaseDetail;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Repository\Repository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GoldImpurityPurchaseService
{
      // initialize protected model variables
      protected $model_gold_impurity;
      protected $model_gold_impurity_detail;
      protected $model_journal_entry;

      protected $common_service;
      protected $journal_entry_service;
      public function __construct()
      {
            // set the model
            $this->model_gold_impurity = new Repository(new GoldImpurityPurchase);
            $this->model_gold_impurity_detail = new Repository(new GoldImpurityPurchaseDetail);
            $this->model_journal_entry = new Repository(new JournalEntry);

            $this->common_service = new CommonService();
            $this->journal_entry_service = new JournalEntryService();
      }

      public function getSource($obj)
      {
            $wh = [];
            if ($obj['posted'] != '') {
                  $wh[] = ['is_posted', $obj['posted']];
            }
            if ($obj['customer_id'] != '') {
                  $wh[] = ['customer_id', $obj['customer_id']];
            }
            $model = $this->model_gold_impurity->getModel()::has('GoldImpurityPurchaseDetail')->where('is_deleted', 0)
                  ->whereBetween('created_at', [date("Y-m-d", strtotime(str_replace('/', '-', $obj['start']))), date("Y-m-d", strtotime(str_replace('/', '-', $obj['end'])))])
                  ->where($wh);

            $data = DataTables::of($model)
                  ->addColumn('check_box', function ($item) {
                        if ($item->is_posted != 1)
                              return '<input type="checkbox" class="sub_chk" value="' . $item->id . '" data-id="' . $item->id . '" >';
                  })
                  ->addColumn('created_at', function ($item) {
                        return $item->created_at->format('d-M-Y');
                  })
                  ->addColumn('customer', function ($item) {
                        return $item->customer_name->name??'';
                  })
                  ->addColumn('is_posted', function ($item) {
                        $badge_color = $item->is_posted == 0 ? 'badge-danger' : 'badge-success';
                        $badge_text = $item->is_posted == 0 ? 'Unposted' : 'Posted';
                        return '<span class="badge ' . $badge_color . '">' . $badge_text . '</span>';
                  })
                  ->addColumn('action', function ($item) {

                        $jvs = '';
                        if ($item->jv_id != null)
                              $jvs .= "filter[]=" . $item->jv_id . "";

                        if ($item->cash_jv_id != null)
                              $jvs .= "&filter[]=" . $item->cash_jv_id . "";

                        if ($item->bank_jv_id != null)
                              $jvs .= "&filter[]=" . $item->bank_jv_id . "";

                        $action_column = '';
                        $unpost = '<a class="text text-danger" id="unpost" data-toggle="tooltip" data-id="' . $item->id . '" data-original-title="Unpost" href="javascript:void(0)"><i class="fa fa-repeat"></i>Unpost</a>';
                        $print_column    = "<a class='text-info mr-2' href='gold-impurity/print/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                        $all_print_column    = "<a class='text-info mr-2' id='Ref' href='javascript:void(0)' data-toggle='tooltip'  data-filter='" . $jvs . "' data-original-title='Accounting'><i title='Accounting' class='nav-icon mr-2 fa fa-eye'></i>Accounting</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteGoldImpurity' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";


                        if (Auth::user()->can('gold_impurity_print'))
                              $action_column .= $print_column;
                        if (Auth::user()->can('gold_impurity_unpost') && $item->is_posted == 1)
                              $action_column .= $unpost;
                        if (Auth::user()->can('gold_impurity_jvs') && $item->is_posted == 1)
                              $action_column .= $all_print_column;

                        if (Auth::user()->can('gold_impurity_delete'))
                              $action_column .= $delete_column;

                        return $action_column;
                  })
                  ->rawColumns(['check_box','created_at','customer', 'is_posted', 'action'])
                  ->addIndexColumn()
                  ->make(true);
            return $data;
      }

      public function getAllGoldImpurity()
      {
            return $this->model_gold_impurity->getModel()::with('customer_name')
                  ->where('is_deleted', 0)
                  ->get();
      }
      public function saveGoldImpurity()
      {
            try {
                  DB::beginTransaction();
                  $obj = [
                        'gold_impurity_purchase_no' => $this->common_service->generateGoldImpurityNo(),
                        'createdby_id' => Auth::User()->id
                  ];
                  $saved_obj = $this->model_gold_impurity->create($obj);

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
                  $GoldImpurityDetail = json_decode($obj['goldImpurityDetail']);
                  $goldImpurityObj = [
                        "customer_id" => $obj['customer_id'],
                        "total_qty" => $obj['total_qty'] ?? 0,
                        "total_weight" => $obj['total_weight'] ?? 0,
                        "total" => $obj['total'],
                        "cash_payment" => $obj['cash_payment'] ?? 0,
                        "bank_payment" => $obj['bank_payment'] ?? 0,
                        "total_payment" => $obj['cash_payment'] + $obj['bank_payment'],
                        "updatedby_id" => Auth::user()->id
                  ];
                  $gold_impurity = $this->model_gold_impurity->update($goldImpurityObj, $obj['id']);

                  foreach ($GoldImpurityDetail as $item) {
                        $GoldImpurityDetailObj = [
                              "gold_impurity_purchase_id" => $obj['id'],
                              "scale_weight" => $item->scale_weight ?? 0.000,
                              "bead_weight" => $item->bead_weight ?? 0.000,
                              "stone_weight" => $item->stone_weight ?? 0.000,
                              "net_weight" => $item->net_weight ?? 0.000,
                              "pure_weight" => $item->pure_weight ?? 0.000,
                              "point" => $item->point ?? 0.000,
                              "gold_rate" => $item->gold_rate ?? 0.000,
                              "total_amount" => $item->total_amount,
                              "createdby_id" => Auth::user()->id
                        ];
                        $gold_impurity_detail = $this->model_gold_impurity_detail->create($GoldImpurityDetailObj);
                  }

                  DB::commit();
            } catch (Exception $e) {
                  return $e;
            }

            return true;
      }

      public function getById($id)
      {
            return $this->model_gold_impurity->getModel()::with('customer_name')->find($id);
      }

      public function GoldImpurityDetail($gold_impurity_id)
      {
            $gold_impurity_detail = $this->model_gold_impurity_detail->getModel()::with([
                  'gold_impurity_purchase'
            ])
                  ->where('gold_impurity_purchase_id', $gold_impurity_id)->get();

            $data = [];
            foreach ($gold_impurity_detail as $item) {
                  $data[] = [
                        "scale_weight" => $item->scale_weight ?? 0.000,
                        "bead_weight" => $item->bead_weight ?? 0.000,
                        "stone_weight" => $item->stone_weight ?? 0.000,
                        "net_weight" => $item->net_weight ?? 0.000,
                        "pure_weight" => $item->pure_weight ?? 0.000,
                        "point" => $item->point ?? 0.000,
                        "gold_rate" => $item->gold_rate ?? 0.000,
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
                  $cash_jv_id = null;
                  $bank_jv_id = null;

                  foreach ($obj['gold_impurity'] as $item) {
                        $gold_impurity = GoldImpurityPurchase::with('customer_name')->find($item);
                        $customer = Customer::find($gold_impurity->customer_id);

                        $journal = Journal::find(config('enum.PV'));
                        $gold_impurity_date = date("Y-m-d", strtotime(str_replace('/', '-', $gold_impurity->created_at)));
                        // Add journal entry
                        $data = [
                              "date" => $gold_impurity_date,
                              "prefix" => $journal->prefix,
                              "journal_id" => $journal->id
                        ];
                        $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

                        $journal_entry = new JournalEntry;
                        $journal_entry->journal_id = $journal->id;
                        $journal_entry->customer_id = $gold_impurity->customer_id;
                        $journal_entry->date_post = $gold_impurity_date;
                        $journal_entry->reference = 'Date :' . $gold_impurity_date . ' Gold Impurity ' . $gold_impurity->gold_impurity_purchase_no . '. Customer is ' . $gold_impurity->customer_name->name;
                        $journal_entry->entryNum = $entryNum;
                        $journal_entry->createdby_id = Auth::User()->id;
                        $journal_entry->save();

                        $journal_entry_id = $journal_entry->id ?? null;

                        // purchase amount
                        if ($gold_impurity->total > 0) {
                              if ($obj['purchase_account_id'] == null || $obj['purchase_account_id'] == '') {
                                    $msg = 'Purchase Account not select but purchase amount is greater then 0';
                                    return $msg;
                              }
                              $purchase_account = Account::find($obj['purchase_account_id']);
                              $Purchase_Amount = str_replace(',', '', $gold_impurity->total ?? 0);
                              // PKR (Debit)
                              $this->journal_entry_service->saveJVDetail(
                                    0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                                    $journal_entry_id, // journal entry id
                                    'Purchase Amount From Gold Impurity Debit Entry', //explaination
                                    $gold_impurity->id, //bill no
                                    0, // check no or 0
                                    $gold_impurity_date, //check date
                                    1, // is credit flag 0 for credit, 1 for debit
                                    $Purchase_Amount, //amount
                                    $purchase_account->id, // account id
                                    $purchase_account->code, // account code
                                    Auth::User()->id //created by id
                              );
                        }

                        // customer credit
                        if ($gold_impurity->total > 0) {
                              if ($customer->account_id == null || $customer->account_id == '') {
                                    $msg = 'Please attach account with customer!';
                                    return $msg;
                              }
                              $customer_account = Account::find($customer->account_id);
                              $customer_amount = str_replace(',', '', $gold_impurity->total ?? 0);
                              // PKR (Credit)
                              $this->journal_entry_service->saveJVDetail(
                                    0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                                    $journal_entry_id, // journal entry id
                                    'Customer Credit From Gold Impurity Credit Entry', //explaination
                                    $gold_impurity->id, //bill no
                                    0, // check no or 0
                                    $gold_impurity_date, //check date
                                    0, // is credit flag 0 for credit, 1 for debit
                                    $customer_amount, //amount
                                    $customer_account->id, // account id
                                    $customer_account->code, // account code
                                    Auth::User()->id //created by id
                              );
                        }

                        if ($gold_impurity->cash_payment > 0) {
                              if ($obj['cash_payment_account_id'] == null || $obj['cash_payment_account_id'] == '') {
                                    $msg = 'Please select cash payment account!';
                                    return $msg;
                              }
                              $cash_payment_account = Account::find($obj['cash_payment_account_id']);
                              $cash_payment = str_replace(',', '', $gold_impurity->cash_payment ?? 0);
                              // PKR (Credit)

                              $cash_jv_id = $this->cashPaymentJV(
                                    $customer_account,
                                    $cash_payment_account,
                                    $cash_payment,
                                    $gold_impurity
                              );
                        }
                        if ($gold_impurity->bank_payment > 0) {
                              if ($obj['bank_payment_account_id'] == null || $obj['bank_payment_account_id'] == '') {
                                    $msg = 'Please select bank payment account!';
                                    return $msg;
                              }
                              $bank_payment_account = Account::find($obj['bank_payment_account_id']);
                              $bank_payment = str_replace(',', '', $gold_impurity->bank_payment ?? 0);
                              // PKR (Credit)

                              $bank_jv_id = $this->bankTransferPaymentJV(
                                    $customer_account,
                                    $bank_payment_account,
                                    $bank_payment,
                                    $gold_impurity
                              );
                        }

                        $gold_impurity->is_posted = 1;
                        $gold_impurity->jv_id = $journal_entry_id;
                        $gold_impurity->cash_jv_id = $cash_jv_id;
                        $gold_impurity->bank_jv_id = $bank_jv_id;
                        $gold_impurity->update();
                  }
                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }
      // Cash Payment JV
      private function cashPaymentJV(
            $customer_account,
            $cash_payment_account,
            $amount,
            $gold_impurity
      ) {
            $journal = Journal::find(config('enum.CPV'));
            $gold_impurity_date = date("Y-m-d", strtotime(str_replace('/', '-', $gold_impurity->created_at)));
            // Add journal entry
            $data = [
                  "date" => $gold_impurity_date,
                  "prefix" => $journal->prefix,
                  "journal_id" => $journal->id
            ];
            $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

            $cash_payment_journal_entry = new JournalEntry;
            $cash_payment_journal_entry->journal_id = $journal->id;
            $cash_payment_journal_entry->customer_id = $gold_impurity->customer_id;
            $cash_payment_journal_entry->date_post = $gold_impurity_date;
            $cash_payment_journal_entry->reference = 'Date :' . $gold_impurity_date . ' Gold Impurity Cash Payment ' . $gold_impurity->gold_impurity_purchase_no . '. Customer is ' . $gold_impurity->customer_name->name;
            $cash_payment_journal_entry->entryNum = $entryNum;
            $cash_payment_journal_entry->createdby_id = Auth::User()->id;
            $cash_payment_journal_entry->save();

            $cash_payment_journal_entry_id = $cash_payment_journal_entry->id ?? null;

            // cash amount
            $Amount = str_replace(',', '', $amount ?? 0);
            // PKR (Debit)
            $this->journal_entry_service->saveJVDetail(
                  0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                  $cash_payment_journal_entry_id, // journal entry id
                  'Cash Payment From Gold Impurity Debit Entry', //explaination
                  $gold_impurity->id, //bill no
                  0, // check no or 0
                  $gold_impurity_date, //check date
                  1, // is credit flag 0 for credit, 1 for debit
                  $Amount, //amount
                  $customer_account->id, // account id
                  $customer_account->code, // account code
                  Auth::User()->id //created by id
            );

            // PKR (Credit)
            $this->journal_entry_service->saveJVDetail(
                  0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                  $cash_payment_journal_entry_id, // journal entry id
                  'Cash Payment From Gold Impurity Credit Entry', //explaination
                  $gold_impurity->id, //bill no
                  0, // check no or 0
                  $gold_impurity_date, //check date
                  0, // is credit flag 0 for credit, 1 for debit
                  $Amount, //amount
                  $cash_payment_account->id, // account id
                  $cash_payment_account->code, // account code
                  Auth::User()->id //created by id
            );

            return $cash_payment_journal_entry_id;
      }
      // Bank Transfer Payment JV
      private function bankTransferPaymentJV(
            $customer_account,
            $bank_payment_account,
            $amount,
            $gold_impurity
      ) {
            $journal = Journal::find(config('enum.BPV'));
            $gold_impurity_date = date("Y-m-d", strtotime(str_replace('/', '-', $gold_impurity->created_at)));
            // Add journal entry
            $data = [
                  "date" => $gold_impurity_date,
                  "prefix" => $journal->prefix,
                  "journal_id" => $journal->id
            ];
            $entryNum = $this->journal_entry_service->generateJournalEntryNum($data);

            $bank_payment_journal_entry = new JournalEntry;
            $bank_payment_journal_entry->journal_id = $journal->id;
            $bank_payment_journal_entry->customer_id = $gold_impurity->customer_id;
            $bank_payment_journal_entry->date_post = $gold_impurity_date;
            $bank_payment_journal_entry->reference = 'Date :' . $gold_impurity_date . ' Gold Impurity Bank Payment ' . $gold_impurity->gold_impurity_purchase_no . '. Customer is ' . $gold_impurity->customer_name->name;
            $bank_payment_journal_entry->entryNum = $entryNum;
            $bank_payment_journal_entry->createdby_id = Auth::User()->id;
            $bank_payment_journal_entry->save();

            $bank_payment_journal_entry_id = $bank_payment_journal_entry->id ?? null;

            // cash amount
            $Amount = str_replace(',', '', $amount ?? 0);
            // PKR (Debit)
            $this->journal_entry_service->saveJVDetail(
                  0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                  $bank_payment_journal_entry_id, // journal entry id
                  'Bank Payment From Gold Impurity Debit Entry', //explaination
                  $gold_impurity->id, //bill no
                  0, // check no or 0
                  $gold_impurity_date, //check date
                  1, // is credit flag 0 for credit, 1 for debit
                  $Amount, //amount
                  $customer_account->id, // account id
                  $customer_account->code, // account code
                  Auth::User()->id //created by id
            );

            // PKR (Credit)
            $this->journal_entry_service->saveJVDetail(
                  0, // currency 0 for PKR, 1 for AU, 2 for Dollar
                  $bank_payment_journal_entry_id, // journal entry id
                  'Bank Payment From Gold Impurity Credit Entry', //explaination
                  $gold_impurity->id, //bill no
                  0, // check no or 0
                  $gold_impurity_date, //check date
                  0, // is credit flag 0 for credit, 1 for debit
                  $Amount, //amount
                  $bank_payment_account->id, // account id
                  $bank_payment_account->code, // account code
                  Auth::User()->id //created by id
            );

            return $bank_payment_journal_entry_id;
      }
      public function unpost($gold_impurity_id)
      {
            try {
                  DB::beginTransaction();

                  $gold_impurity = $this->model_gold_impurity->getModel()::find($gold_impurity_id);

                  if ($gold_impurity->jv_id != null) {
                        // Journal entry delete
                        $journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->jv_id);
                        $journal_entry->is_deleted = 1;
                        $journal_entry->deletedby_id = Auth::user()->id;
                        $journal_entry->update();
                  }
                  if ($gold_impurity->cash_jv_id != null) {
                        // Journal entry delete
                        $cash_journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->cash_jv_id);
                        $cash_journal_entry->is_deleted = 1;
                        $cash_journal_entry->deletedby_id = Auth::user()->id;
                        $cash_journal_entry->update();
                  }
                  if ($gold_impurity->bank_jv_id != null) {
                        // Journal entry delete
                        $bank_journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->bank_jv_id);
                        $bank_journal_entry->is_deleted = 1;
                        $bank_journal_entry->deletedby_id = Auth::user()->id;
                        $bank_journal_entry->update();
                  }

                  // sale update
                  $gold_impurity->is_posted = 0;
                  $gold_impurity->jv_id = Null;
                  $gold_impurity->cash_jv_id = Null;
                  $gold_impurity->bank_jv_id = Null;
                  $gold_impurity->update();

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }

      public function deleteById($gold_impurity_id)
      {
            try {
                  DB::beginTransaction();

                  $gold_impurity = $this->model_gold_impurity->getModel()::find($gold_impurity_id);

                  if ($gold_impurity->jv_id != null) {
                        // Journal entry delete
                        $journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->jv_id);
                        $journal_entry->is_deleted = 1;
                        $journal_entry->deletedby_id = Auth::user()->id;
                        $journal_entry->update();
                  }
                  if ($gold_impurity->cash_jv_id != null) {
                        // Journal entry delete
                        $cash_journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->cash_jv_id);
                        $cash_journal_entry->is_deleted = 1;
                        $cash_journal_entry->deletedby_id = Auth::user()->id;
                        $cash_journal_entry->update();
                  }
                  if ($gold_impurity->bank_jv_id != null) {
                        // Journal entry delete
                        $bank_journal_entry = $this->model_journal_entry->getModel()::find($gold_impurity->bank_jv_id);
                        $bank_journal_entry->is_deleted = 1;
                        $bank_journal_entry->deletedby_id = Auth::user()->id;
                        $bank_journal_entry->update();
                  }

                  // sale update
                  $gold_impurity->is_deleted = 1;
                  $gold_impurity->deletedby_id = Auth::user()->id;
                  $gold_impurity->is_posted = 0;
                  $gold_impurity->jv_id = Null;
                  $gold_impurity->cash_jv_id = Null;
                  $gold_impurity->bank_jv_id = Null;
                  $gold_impurity->update();

                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }

}

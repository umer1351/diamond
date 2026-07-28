<?php

namespace App\Services\Concrete;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\SaleOrder;
use App\Repository\Repository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JournalEntryService
{
      protected $model_journal_entry;
      protected $model_journal_entry_detail;

      public function __construct()
      {
            // set the model
            $this->model_journal_entry = new Repository(new JournalEntry);
            $this->model_journal_entry_detail = new Repository(new JournalEntryDetail);
      }

      //Journal
      public function getJournalEntrySource($obj)
      {
            $wh = [];
            $wh[] = ['is_deleted', 0];
            if ($obj['journal_id'] != '' && $obj['journal_id'] != null) {
                  $wh[] = ['journal_id', $obj['journal_id']];
            }
            if ($obj['supplier_id'] != '' && $obj['supplier_id'] != null) {
                  $wh[] = ['supplier_id', $obj['supplier_id']];
            }
            if ($obj['customer_id'] != '' && $obj['customer_id'] != null) {
                  $wh[] = ['supplier_id', $obj['customer_id']];
            }
            $model = $this->model_journal_entry->getModel()::with('journal_name')
                  ->where($wh)
                  ->where('date_post', '>=', $obj['from_date'] . " 00:00:00")->where('date_post', '<=', $obj['to_date'] . " 23:59:59")
                  ->orderBy('date_post', 'DESC');
            $data = DataTables::of($model)
                  ->addColumn('journal', function ($item) {
                        return $item->journal_name->name ?? '';
                  })
                  ->addColumn('debit', function ($item) {
                        return  number_format(JournalEntryDetail::where("journal_entry_id", $item->id)->sum("debit"), 2);
                  })
                  ->addColumn('credit', function ($item) {
                        return  number_format(JournalEntryDetail::where("journal_entry_id", $item->id)->sum("credit"), 2);
                  })
                  ->addColumn('action', function ($item) {
                        $action_column = '';
                        $edit_column    = "<a class='text-warning mr-2' href='journal-entries/edit/" . $item->id . "' data-toggle='tooltip'><i title='Edit' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                        $print_column    = "<a class='text-primary mr-2' target='_blank' href='journal-entries/print/" . $item->id . "' data-toggle='tooltip'><i title='Print' class='nav-icon mr-2 fa fa-print'></i>Print</a>";
                        $delete_column    = "<a class='text-danger mr-2'  id='deleteJournalEntry' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                        if (Auth::user()->can('journal_entries_edit'))
                              $action_column .= $edit_column;
                        // if (Auth::user()->can('journal_entries_print'))
                        //       $action_column .= $print_column;
                        if (Auth::user()->can('journal_entries_delete'))
                              $action_column .= $delete_column;

                        return $action_column;
                  })
                  ->rawColumns(['journal', 'debit', 'credit', 'action'])
                  ->make(true);
            return $data;
      }

      public function generateJournalEntryNum($obj)
      {
            $month = Carbon::parse($obj['date'])->format('m');
            $year = Carbon::parse($obj['date'])->format('Y');
            $currentPrefix = $obj['prefix'];
            $estmEntryNum = "$currentPrefix-$year-$month";
            $journal_id = $obj['journal_id'];
            $query = "SELECT MAX(id) as count , journal_id 
                  FROM `journal_entries` 
                  WHERE MONTH(`date_post`)= '$month' AND YEAR(`date_post`)= '$year' AND journal_id = '$journal_id'
                  GROUP BY journal_id,MONTH(`date_post`)";
            $journalEntry =  DB::select(DB::raw($query));
            $count = 1;
            if (sizeof($journalEntry) > 0) {
                  $count = $journalEntry[0]->count + 1;
            }
            $count = str_pad($count, 4, "0", STR_PAD_LEFT);
            $estmEntryNum .= "-$count";

            return $estmEntryNum;
      }
      public function getJournalIdByEntryNum($entryNum)
      {
            $journal = $this->model_journal_entry->getModel()::where('entryNum', '=', $entryNum)->first();

            return $journal->id;
      }

      // save Journal
      public function saveJournalEntry($obj)
      {
            if ($obj != null) {
                  $saved_obj = $this->model_journal_entry->create($obj);

                  if (!$saved_obj)
                        return false;

                  return $saved_obj;
            }
      }
      public function saveJournalEntryDetail($obj)
      {

            $saved_obj = $this->model_journal_entry_detail->create($obj);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      public function saveJVDetail(
            $currency,
            $journal_entry_id,
            $explanation,
            $bill_no,
            $check_no = null,
            $check_date = null,
            $is_credit = 0,
            $amount = 0,
            $acc_head_id,
            $account_code,
            $createdBy
      ) {
            $credit = 0;
            $debit = 0;
            if ($is_credit == 0) {
                  $credit = $amount;
            } else {
                  $debit = $amount;
            }
            $entry_detail = [
                  'currency' => $currency,
                  'credit' => $credit,
                  'journal_entry_id' => $journal_entry_id,
                  'explanation' => $explanation ?? '',
                  'bill_no' => $bill_no ?? '',
                  'check_no' => $check_no ?? '',
                  'check_date' => $check_date,
                  'debit' => $debit,
                  'account_id' => $acc_head_id,
                  'createdby_id' =>  $createdBy,
                  'amount' => $amount,
                  'account_code' => $account_code,
                  'amount_in_words' => $this->numberToWord($amount)
            ];
            $save_entry = DB::table('journal_entry_details')->insert($entry_detail);
            if ($save_entry)
                  return true;

            return false;
      }

      public function getJournalEntryDetailByJournalEntryId($id)
      {
            $journal_entry_detail = $this->model_journal_entry_detail->getModel()::with('account_name')->where('journal_entry_id', $id)->get();

            if (!$journal_entry_detail)
                  return false;

            return $journal_entry_detail;
      }

      public function gridJournalEdit($id)
      {
            $grid_journal = DB::table('journals')
                  ->leftjoin('journal_entries', 'journals.id', 'journal_entries.journal_id')
                  ->leftjoin('journal_entry_details', 'journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                  ->leftjoin('accounts', 'journal_entry_details.account_id', '=', 'accounts.id')
                  ->where('journal_entries.id', $id)
                  ->select('*', 'journals.name', 'journal_entries.journal_id as jentries_id', 'journal_entries.id as j_id', 'journal_entries.date_post as Date', 'journal_entry_details.id as tbl_id', 'journal_entry_details.debit as Debit', 'journal_entry_details.credit as Credit', 'journal_entry_details.bill_no as BillNo', 'journal_entry_details.check_no as CheckNo', 'accounts.code as Code', 'accounts.name as Account', 'journal_entry_details.explanation as Explanation', 'accounts.id as account_id')
                  ->get();
            return $grid_journal;
      }
      // update Journal
      public function updateJournalEntry($obj)
      {

            $this->model_journal_entry->update($obj, $obj['id']);
            $saved_obj = $this->model_journal_entry->find($obj['id']);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }


      // get by id
      public function getJournalEntryById($id)
      {
            $journal_entry = $this->model_journal_entry->getModel()::with(['journal_name', 'supplier_name'])->find($id);

            if (!$journal_entry)
                  return false;

            return $journal_entry;
      }


      public function deleteDetailByJournalEntry($id)
      {

            try {
                  DB::beginTransaction();
                  $journal_entry_detail = JournalEntryDetail::where('journal_entry_id', $id)->delete();

                  if (!$journal_entry_detail)
                        return false;
                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return $journal_entry_detail;
      }
      public function deleteJournalEntryById($id)
      {

            try {
                  DB::beginTransaction();
                  $journal_entry = JournalEntry::find($id);
                  $journal_entry->is_deleted = 1;
                  $journal_entry->deletedby_id = Auth::user()->id;
                  $journal_entry->update();
                  DB::commit();
            } catch (Exception $e) {

                  DB::rollback();
                  throw $e;
            }
            return true;
      }

      public function getJournalEntryByIds($journal_entries)
      {
            $journal_entry = $this->model_journal_entry->getModel()::with(['journal_name', 'supplier_name'])
                  ->whereIn('id', $journal_entries)->get();
            $data = [];
            foreach ($journal_entry as $item) {
                  $data[] = [
                        "entryNum"                    => $item->entryNum ?? '',
                        "date_post"                   => date('d-M-Y', strtotime($item->date_post ?? '')),
                        "journal_name"                => $item->journal_name->name ?? '',
                        "supplier_name"                 => $item->supplier_name->name ?? '',
                        "reference"                   => $item->reference ?? '',
                        "journal_entry_detail"        => $this->model_journal_entry_detail->getModel()::with('account_name')->where('journal_entry_id', $item->id)->get()
                  ];
            }

            if (!$data)
                  return false;

            return $data;
      }
      public function getCustomerBalanceByAccountId($customer_id,$account_id,$currency)
      {
            $balance = $this->model_journal_entry->getModel()::where('journal_entries.date_post', '<=', Carbon::now()->format('Y-m-d'))
                  ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                  ->where('journal_entries.customer_id', $customer_id)
                  ->where('journal_entry_details.account_id', $account_id)
                  ->where('journal_entry_details.currency', $currency)
                  ->where('journal_entries.is_deleted', 0)
                  ->selectRaw('SUM(journal_entry_details.credit) - SUM(journal_entry_details.debit) AS balance')
                  ->value('balance');

            return $balance;
      }
      public function getSupplierBalanceByAccountId($supplier_id,$account_id,$currency)
      {
            $balance = $this->model_journal_entry->getModel()::where('journal_entries.date_post', '<=', Carbon::now()->format('Y-m-d'))
                  ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                  ->where('journal_entries.supplier_id', $supplier_id)
                  ->where('journal_entry_details.account_id', $account_id)
                  ->where('journal_entry_details.currency', $currency)
                  ->where('journal_entries.is_deleted', 0)
                  ->selectRaw('SUM(journal_entry_details.credit) - SUM(journal_entry_details.debit) AS balance')
                  ->value('balance');

            return $balance;
      }
      public function getSaleOrderAdvanceById($sale_order_id)
      {
            $sale_order = SaleOrder::find($sale_order_id);
            $balance = $this->model_journal_entry->getModel()::join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                  ->where('journal_entries.sale_order_id', $sale_order_id)
                  ->where('journal_entry_details.account_id', $sale_order->customer_name->account_id)
                  ->where('journal_entry_details.currency', 0)
                  ->where('journal_entries.is_deleted', 0)
                  ->selectRaw('SUM(journal_entry_details.credit) - SUM(journal_entry_details.debit) AS balance')
                  ->value('balance');

            return $balance;
      }
      public function getSaleOrderPayments($sale_order_id)
      {
            $sale_order = SaleOrder::find($sale_order_id);
            $payments = $this->model_journal_entry->getModel()::join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                  ->where('journal_entries.sale_order_id', $sale_order_id)
                  ->where('journal_entry_details.account_id', $sale_order->customer_name->account_id)
                  ->where('journal_entry_details.currency', 0)
                  ->where('journal_entries.is_deleted', 0)
                  ->select('journal_entry_details.credit',DB::raw("DATE_FORMAT(journal_entries.date_post, '%d %b %Y') AS date_post"))->get();

            return $payments;
      }
      public function numberToWord($num = '')
      {
            $num    = (string) ((int) $num);

            if (
                  (int) ($num) && ctype_digit($num)
            ) {
                  $words  = array();

                  $num    = str_replace(
                        array(',', ' '),
                        '',
                        trim($num)
                  );

                  $list1  = array(
                        '',
                        'one',
                        'two',
                        'three',
                        'four',
                        'five',
                        'six',
                        'seven',
                        'eight',
                        'nine',
                        'ten',
                        'eleven',
                        'twelve',
                        'thirteen',
                        'fourteen',
                        'fifteen',
                        'sixteen',
                        'seventeen',
                        'eighteen',
                        'nineteen'
                  );

                  $list2  = array(
                        '',
                        'ten',
                        'twenty',
                        'thirty',
                        'forty',
                        'fifty',
                        'sixty',
                        'seventy',
                        'eighty',
                        'ninety',
                        'hundred'
                  );

                  $list3  = array(
                        '',
                        'thousand',
                        'million',
                        'billion',
                        'trillion',
                        'quadrillion',
                        'quintillion',
                        'sextillion',
                        'septillion',
                        'octillion',
                        'nonillion',
                        'decillion',
                        'undecillion',
                        'duodecillion',
                        'tredecillion',
                        'quattuordecillion',
                        'quindecillion',
                        'sexdecillion',
                        'septendecillion',
                        'octodecillion',
                        'novemdecillion',
                        'vigintillion'
                  );

                  $num_length = strlen($num);
                  $levels = (int) (($num_length + 2) / 3);
                  $max_length = $levels * 3;
                  $num    = substr('00' . $num, -$max_length);
                  $num_levels = str_split($num, 3);

                  foreach ($num_levels as $num_part) {
                        $levels--;
                        $hundreds   = (int) ($num_part / 100);
                        $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
                        $tens       = (int) ($num_part % 100);
                        $singles    = '';

                        if (
                              $tens < 20
                        ) {
                              $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                        } else {
                              $tens = (int) ($tens / 10);
                              $tens = ' ' . $list2[$tens] . ' ';
                              $singles = (int) ($num_part % 10);
                              $singles = ' ' . $list1[$singles] . ' ';
                        }
                        $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
                  }
                  $commas = count($words);
                  if ($commas > 1) {
                        $commas = $commas - 1;
                  }

                  $words  = implode(', ', $words);

                  $words  = trim(str_replace(
                        ' ,',
                        ',',
                        ucwords($words)
                  ), ', ');
                  if ($commas) {
                        $words  = str_replace(
                              ',',
                              ' and',
                              $words
                        );
                  }

                  return $words;
            } else if (!((int) $num)) {
                  return 'Zero';
            }
            return '';
      }
}

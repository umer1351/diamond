<?php

namespace App\Services\Concrete;

use App\Models\Customer;
use App\Models\FinishProduct;
use App\Models\FinishProductBead;
use App\Models\FinishProductDiamond;
use App\Models\FinishProductStone;
use App\Models\JournalEntry;
use App\Models\OtherProduct;
use App\Models\Product;
use App\Models\RattiKaatDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SaleDetailBead;
use App\Models\SaleDetailDiamond;
use App\Models\SaleDetailStone;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Warehouse;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportService
{
      protected $model_product;
      protected $model_journal_entry;
      protected $model_finish_product;
      protected $model_finish_product_bead;
      protected $model_finish_product_stone;
      protected $model_finish_product_diamond;
      protected $model_sale;
      protected $model_sale_detail;
      protected $model_sale_detail_bead;
      protected $model_sale_detail_stone;
      protected $model_sale_detail_diamond;
      protected $model_warehouse;
      protected $model_other_product;
      protected $model_transaction;
      public function __construct()
      {
            // set the model
            $this->model_product = new Repository(new Product);
            $this->model_journal_entry = new Repository(new JournalEntry);
            $this->model_finish_product = new Repository(new FinishProduct);
            $this->model_finish_product_bead = new Repository(new FinishProductBead);
            $this->model_finish_product_stone = new Repository(new FinishProductStone);
            $this->model_finish_product_diamond = new Repository(new FinishProductDiamond);
            $this->model_sale = new Repository(new Sale);
            $this->model_sale_detail = new Repository(new SaleDetail);
            $this->model_sale_detail_bead = new Repository(new SaleDetailBead);
            $this->model_sale_detail_stone = new Repository(new SaleDetailStone);
            $this->model_sale_detail_diamond = new Repository(new SaleDetailDiamond);
            $this->model_warehouse = new Repository(new Warehouse);
            $this->model_other_product = new Repository(new OtherProduct);
            $this->model_transaction = new Repository(new Transaction);
      }

      // Ledger Report
      public function ledgerReport($obj)
      {
            $opening_balance = [];
            $wh = [];
            $whIn = [];
            if (isset($obj['supplier_id']) && $obj['supplier_id'] != 0 && $obj['supplier_id'] != "") {
                  $wh[] = ['journal_entries.supplier_id', '=', $obj['supplier_id']];
                  $supplier = Supplier::find($obj['supplier_id']);
                  $wh[] = ['journal_entry_details.account_id', '=', $supplier->account_id];
            }
            if (isset($obj['customer_id']) && $obj['customer_id'] != 0 && $obj['customer_id'] != "") {
                  $wh[] = ['journal_entries.customer_id', '=', $obj['customer_id']];
                  $customer = Customer::find($obj['customer_id']);
                  $wh[] = ['journal_entry_details.account_id', '=', $customer->account_id];
            }
            if (isset($obj['currency']) && $obj['currency'] != "") {
                  $wh[] = ['journal_entry_details.currency', '=', $obj['currency']];
            }
            if (isset($obj['account_id']) && $obj['account_id'] != 0 && $obj['account_id'] != "") {
                  $journal_entry = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->where('journal_entries.date_post', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                        ->join('accounts', 'journal_entry_details.account_id', 'accounts.id')
                        ->whereIn('journal_entry_details.account_id', $obj['account_id'])
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->orderBy('journal_entries.date_post', 'ASC')
                        ->orderBy('journal_entry_details.journal_entry_id', 'ASC')
                        ->get();
                  $opening_balance = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '<', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                        ->whereIn('journal_entry_details.account_id', $obj['account_id'])
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->orderBy('journal_entries.date_post', 'ASC')
                        ->orderBy('journal_entry_details.journal_entry_id', 'ASC')
                        ->get();
                  return [
                        "journal_entry" => $journal_entry,
                        "opening_balance" => $opening_balance
                  ];
            } else {
                  $journal_entry = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->where('journal_entries.date_post', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                        ->join('accounts', 'journal_entry_details.account_id', 'accounts.id')
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->orderBy('journal_entries.date_post', 'ASC')
                        ->orderBy('journal_entry_details.journal_entry_id', 'ASC')
                        ->get();
                  $opening_balance = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '<', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', 'journal_entries.id')
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->orderBy('journal_entries.date_post', 'ASC')
                        ->orderBy('journal_entry_details.journal_entry_id', 'ASC')
                        ->get();
                  return [
                        "journal_entry" => $journal_entry,
                        "opening_balance" => $opening_balance
                  ];
            }
      }

      // Tag History Report
      public function tagHistoryReport($obj)
      {
            $wh = [];
            if (isset($obj['finish_product_id']) && $obj['finish_product_id'] != 0 && $obj['finish_product_id'] != "") {
                  $wh[] = ['id', '=', $obj['finish_product_id']];
            }
            if (isset($obj['product_id']) && $obj['product_id'] != 0 && $obj['product_id'] != "") {
                  $wh[] = ['product_id', '=', $obj['product_id']];
            }
            if (isset($obj['warehouse_id']) && $obj['warehouse_id'] != 0 && $obj['warehouse_id'] != "") {
                  $wh[] = ['warehouse_id', '=', $obj['warehouse_id']];
            }
            $finish_products = $this->model_finish_product->getModel()::with(
                  [
                        'product',
                        'warehouse',
                        'created_by'
                  ]
            )
                  ->where('created_at', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                  ->where('created_at', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                  ->where('is_deleted', 0)
                  ->where('is_parent', 0)
                  ->where($wh)
                  ->orderBy('created_at', 'ASC')
                  ->get();
            $data = [];
            foreach ($finish_products as $item) {
                  $data[] = [
                        "product_name" => $item->product->name ?? '',
                        "tag_no" => $item->tag_no ?? '',
                        "scale_weight" => $item->scale_weight ?? 0,
                        "gross_weight" => $item->gross_weight ?? 0,
                        "bead_weight" => $item->bead_weight ?? 0,
                        "total_bead_price" => $item->total_bead_price ?? 0,
                        "stones_weight" => $item->stones_weight ?? 0,
                        "total_stones_price" => $item->total_stones_price ?? 0,
                        "diamond_weight" => $item->diamond_weight ?? 0,
                        "total_diamond_price" => $item->total_diamond_price ?? 0,
                        "waste" => $item->waste ?? 0,
                        "quantity" => 1,
                        "createdby" => $item->created_by->name ?? '',
                        "date_time" => $item->created_at->format('d M Y h:i:s A'),
                        "f_beads" => $this->model_finish_product_bead->getModel()::where('finish_product_id', $item->id)->where('is_deleted', 0)->get(),
                        "f_stones" => $this->model_finish_product_stone->getModel()::where('finish_product_id', $item->id)->where('is_deleted', 0)->get(),
                        "f_diamonds" => $this->model_finish_product_diamond->getModel()::where('finish_product_id', $item->id)->where('is_deleted', 0)->get(),
                        "sale_detail" => $this->model_sale_detail->getModel()::with('sale')->where('finish_product_id', $item->id)->where('is_deleted', 0)->first(),
                  ];
            }
            return $data;
      }

      // Profit & Loss
      public function getProfitLossReport($obj)
      {

            $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date'])));
            $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date'])));
            $currency = $obj['currency'] ?? 2;


            $otherIncomeAccounts = DB::select(DB::raw("SELECT accp.id as head_id,acct.`type`,accp.`name`,accp.`code`,acct.`id`,
                            SUM(CASE WHEN jed.debit > 0 THEN jed.`debit` ELSE 0 END) AS Debit,
                            SUM(CASE WHEN jed.credit > 0 THEN jed.`credit` ELSE 0 END) AS Credit
                            FROM journal_entry_details jed
                            JOIN `accounts` acch ON acch.`id`=jed.`account_id`
                            JOIN `accounts` accp ON accp.`id`=acch.`parent_id`
                            JOIN `journal_entries` je ON je.`id`=jed.`journal_entry_id`
                            JOIN `account_types` acct ON acct.`id`=accp.`account_type_id`
                            WHERE acct.id IN(5) AND date(je.date_post) BETWEEN '$start_date' AND '$end_date' AND jed.currency=$currency
                            AND je.is_deleted is null
                            GROUP BY accp.id,acct.type,accp.`name`,accp.`code`,acct.`id`
                            ORDER BY accp.code,acct.type"));

            $revenueAccounts = DB::select(DB::raw("SELECT accp.id as head_id,acct.`type`,accp.`name`,accp.`code`,acct.`id`,
                            SUM(CASE WHEN jed.debit > 0 THEN jed.`debit` ELSE 0 END) AS Debit,
                            SUM(CASE WHEN jed.credit > 0 THEN jed.`credit` ELSE 0 END) AS Credit
                            FROM journal_entry_details jed
                            JOIN `accounts` acch ON acch.`id`=jed.`account_id`
                            JOIN `accounts` accp ON accp.`id`=acch.`parent_id`
                            JOIN `journal_entries` je ON je.`id`=jed.`journal_entry_id`
                            JOIN `account_types` acct ON acct.`id`=accp.`account_type_id`
                            WHERE acct.id IN(9) AND date(je.date_post) BETWEEN '$start_date' AND '$end_date' AND jed.currency=$currency
                            AND je.is_deleted is null
                            GROUP BY accp.id,acct.type,accp.`name`,accp.`code`,acct.`id`
                            ORDER BY accp.code,acct.type"));

            $costRevenueAccounts = DB::select(DB::raw("SELECT accp.id as head_id,acct.`type`,accp.`name`,accp.`code`,acct.`id`,
                            SUM(CASE WHEN jed.debit > 0 THEN jed.`debit` ELSE 0 END) AS Debit,
                            SUM(CASE WHEN jed.credit > 0 THEN jed.`credit` ELSE 0 END) AS Credit
                            FROM journal_entry_details jed
                            JOIN `accounts` acch ON acch.`id`=jed.`account_id`
                            JOIN `accounts` accp ON accp.`id`=acch.`parent_id`
                            JOIN `journal_entries` je ON je.`id`=jed.`journal_entry_id`
                            JOIN `account_types` acct ON acct.`id`=accp.`account_type_id`
                            WHERE acct.id IN(10) AND date(je.date_post) BETWEEN '$start_date' AND '$end_date' AND jed.currency=$currency
                            AND je.is_deleted is null
                            GROUP BY accp.id,acct.type,accp.`name`,accp.`code`,acct.`id`
                            ORDER BY accp.code,acct.type"));

            $directExpenseAccounts = DB::select(DB::raw("SELECT accp.id as head_id,acct.`type`,accp.`name`,accp.`code`,acct.`id`,
                            SUM(CASE WHEN jed.debit > 0 THEN jed.`debit` ELSE 0 END) AS Debit,
                            SUM(CASE WHEN jed.credit > 0 THEN jed.`credit` ELSE 0 END) AS Credit
                            FROM journal_entry_details jed
                            JOIN `accounts` acch ON acch.`id`=jed.`account_id`
                            JOIN `accounts` accp ON accp.`id`=acch.`parent_id`
                            JOIN `journal_entries` je ON je.`id`=jed.`journal_entry_id`
                            JOIN `account_types` acct ON acct.`id`= accp.`account_type_id`
                            WHERE acct.id IN(16) AND date(je.date_post) BETWEEN '$start_date' AND '$end_date' AND jed.currency=$currency
                            AND je.is_deleted is null
                            GROUP BY accp.id,acct.type,accp.`name`,accp.`code`,acct.`id`
                            ORDER BY accp.code,acct.type"));
            $indirectExpenseAccounts = DB::select(DB::raw("SELECT accp.id as head_id,acct.`type`,accp.`name`,accp.`code`,acct.`id`,
                            SUM(CASE WHEN jed.debit > 0 THEN jed.`debit` ELSE 0 END) AS Debit,
                            SUM(CASE WHEN jed.credit > 0 THEN jed.`credit` ELSE 0 END) AS Credit
                            FROM journal_entry_details jed
                            JOIN `accounts` acch ON acch.`id`=jed.`account_id`
                            JOIN `accounts` accp ON accp.`id`=acch.`parent_id`
                            JOIN `journal_entries` je ON je.`id`=jed.`journal_entry_id`
                            JOIN `account_types` acct ON acct.`id`= accp.`account_type_id`
                            WHERE acct.id IN(11) AND date(je.date_post) BETWEEN '$start_date' AND '$end_date' AND jed.currency=$currency
                            AND je.is_deleted is null
                            GROUP BY accp.id,acct.type,accp.`name`,accp.`code`,acct.`id`
                            ORDER BY accp.code,acct.type"));



            $data = [
                  "otherIncomeAccounts" => $otherIncomeAccounts,
                  "revenueAccounts" => $revenueAccounts,
                  "costRevenueAccounts" => $costRevenueAccounts,
                  "directExpenseAccounts" => $directExpenseAccounts,
                  "indirectExpenseAccounts" => $indirectExpenseAccounts
            ];
            return $data;
      }

      // Stock Ledger
      public function getStockLedgerReport($obj)
      {

            $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date'] . ' 00:00:00')));
            $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date'] . ' 23:59:59')));

            $warehouses = isset($obj['warehouse_id']) ?
                  $this->model_warehouse->getModel()::where('id', $obj['warehouse_id'])->where('is_deleted', 0)->get() :
                  $this->model_warehouse->getModel()::where('is_deleted', 0)->get();

            $response = [];

            foreach ($warehouses as $warehouse) {
                  $qry_str = "SELECT prod.id, prod.name AS product, other_product_units.name AS unit,
            SUM(IFNULL(CASE WHEN transactions.type IN (0,3)  AND transactions.qty>0 AND transactions.unit_price>0 AND transactions.is_deleted=0 THEN transactions.unit_price * transactions.qty ELSE 0 END, 0)) / SUM(CASE WHEN transactions.type IN (0,3) AND transactions.unit_price>0  AND transactions.qty>0 AND transactions.is_deleted=0 THEN transactions.qty END)
             AS unit_price,
            (SUM(IFNULL(CASE WHEN transactions.type IN (0,3) AND date(transactions.date) < '$start_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) + 
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NOT NULL AND date(transactions.date) < '$start_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0)))-
            (SUM(IFNULL(CASE WHEN transactions.type IN (1) AND date(transactions.date) < '$start_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) +
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NULL AND date(transactions.date) < '$start_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0)))
             AS opening_qty,
            SUM(IFNULL(CASE WHEN transactions.type IN (0,3) AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) +
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NOT NULL AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0))
             AS stock_in_qty,
            SUM(IFNULL(CASE WHEN transactions.type IN (1) AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) +
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NULL AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0))
             AS stock_out_qty
        FROM other_products prod
        JOIN other_product_units ON other_product_units.id = prod.other_product_unit_id
        LEFT JOIN transactions ON transactions.other_product_id = prod.id 
            AND transactions.is_deleted = 0
        GROUP BY prod.id, prod.name, other_product_units.name";

                  $data = DB::select(DB::raw($qry_str));
                  $response[] = [
                        "warehouse_name" => $warehouse->name,
                        "data" => $data
                  ];
            }
            return $response;
      }

      // Product Ledger
      public function getProductLedgerReport($obj)
      {
            $wh = [];
            $wearhouses = [];
            if ($obj['other_product_id'] > 0) {
                  $wh[] = ['id', $obj['other_product_id']];
            }
            if ($obj['warehouse_id'] > 0) {
                  $wearhouses[] = ['warehouse_id', $obj['warehouse_id']];
            }
            $other_products = $this->model_other_product->getModel()::with(['other_product_unit'])
                  ->where($wh)
                  ->where('is_deleted', 0)
                  ->get();
            $data = [];
            foreach ($other_products as $item) {

                  $total_stock_in = 0.0;
                  $total_stock_out = 0.0;
                  $transaction_data = [];

                  // Opening Stock
                  $opening_stock = $this->model_transaction->getModel()::where('other_product_id', $item->id)
                        ->where('date', '<', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->where($wearhouses)
                        ->where('is_deleted', 0)
                        ->orderBy('date', 'ASC')
                        ->get();

                  $open_stock = 0;
                  foreach ($opening_stock as $item1) {
                        if ($item1->type == 0) {
                              $open_stock = $open_stock + $item1->qty;
                        } elseif ($item1->type == 1) {
                              $open_stock = $open_stock - $item1->qty;
                        } elseif ($item1->type == 2 && $item1->stock_taking_link_id == null) {
                              $open_stock = $open_stock - ($item1->qty >= 0) ? $item1->qty : (-1) * $item1->qty;
                        } elseif ($item1->type == 2 && $item1->stock_taking_link_id > 0) {
                              $open_stock = $open_stock + ($item1->qty >= 0) ? $item1->qty : (-1) * $item1->qty;
                        } elseif ($item1->type == 3) {
                              $open_stock = $open_stock + $item1->qty;
                        }
                  }
                  $stock = $open_stock;
                  $transactions = $this->model_transaction->getModel()::with(['other_product', 'warehouse_name'])
                        ->where('date', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))) . " 00:00:00")
                        ->where('date', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))) . " 23:59:59")
                        ->where('other_product_id', $item->id)
                        ->where($wearhouses)
                        ->where('is_deleted', 0)
                        ->orderBy('date', 'ASC')
                        ->get();

                  foreach ($transactions as $item1) {
                        $stock_in = 0;
                        $stock_out = 0;
                        $type = '';

                        if ($item1->type == 0) {
                              $type = 'Purchase';
                              $stock = $stock + $item1->qty;
                              $stock_in = $item1->qty;
                        } elseif ($item1->type == 1) {
                              $type = 'Sale';
                              $stock = $stock - $item1->qty;
                              $stock_out = $item1->qty;
                        } elseif ($item1->type == 3) {

                              $type = 'Opening Stock';
                              $stock = $stock + $item1->qty;
                              $stock_in = $item1->qty;
                        } elseif ($item1->type == 2 && $item1->stock_taking_link_id == null) {
                              $type = 'Stock Taking';
                              if ($item1->qty >= 0) {
                                    $stock = $stock - $item1->qty;
                              } else {
                                    $stock = abs($stock) + $item1->qty;
                              }
                              $stock_out = ($item1->qty >= 0) ? $item1->qty : $item1->qty;
                        } elseif ($item1->type == 2 && $item1->stock_taking_link_id > 0) {

                              $type = 'Stock Taking';
                              if ($item1->qty >= 0) {
                                    $stock = $stock + $item1->qty;
                              } else {
                                    $stock = abs($stock) - $item1->qty;
                              }
                              $stock_in = ($item1->qty >= 0) ? $item1->qty : $item1->qty;
                        }
                        $total_stock_in += $stock_in;
                        $total_stock_out += $stock_out;

                        $transaction_data[] = [
                              "date" => $item1->date,
                              "warehouse" => $item1->warehouse_name->name ?? '',
                              "type" => $type,
                              "stock_in" => $stock_in,
                              "stock_out" => $stock_out,
                              "stock" => $stock
                        ];
                  }
                  $data[] = [
                        "product_name" => $item->name ?? '',
                        "opening_stock" => $open_stock,
                        "report" => $transaction_data,
                        "total_stock" => $stock,
                        "total_stock_in" => $total_stock_in,
                        "total_stock_out" => $total_stock_out
                  ];
            }
            return $data;
      }

      //product consumption

      public function getProductConsumptionReport($obj)
      {
            set_time_limit(0);

            $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date'] . ' 00:00:00')));
            $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date'] . ' 23:59:59')));

            $warehouses = isset($obj['warehouse_id']) ?
                  $this->model_warehouse->getModel()::where('id', $obj['warehouse_id'])->where('is_deleted', 0)->get() :
                  $this->model_warehouse->getModel()::where('is_deleted', 0)->get();

            $response = [];

            foreach ($warehouses as $warehouse) {
                  $qry_str = "SELECT prod.id, prod.name AS product, other_product_units.name AS unit,
            SUM(IFNULL(CASE WHEN transactions.type IN (0,3)  AND transactions.qty>0 AND transactions.unit_price>0 AND transactions.is_deleted=0 THEN transactions.unit_price * transactions.qty ELSE 0 END, 0)) / SUM(CASE WHEN transactions.type IN (0,3) AND transactions.unit_price>0  AND transactions.qty>0 AND transactions.is_deleted=0 THEN transactions.qty END)
             AS unit_price,
            SUM(IFNULL(CASE WHEN transactions.type IN (0,3) AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) +
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NOT NULL AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0))
             AS stock_in_qty,
            SUM(IFNULL(CASE WHEN transactions.type IN (1) AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END, 0)) +
            SUM(IFNULL(CASE WHEN type = 2 AND stock_taking_link_id IS NULL AND date(transactions.date) BETWEEN '$start_date' AND '$end_date' AND transactions.is_deleted=0 AND transactions.warehouse_id = {$warehouse->id} THEN transactions.qty ELSE 0 END,0))
             AS stock_out_qty
                  FROM other_products prod
                  JOIN other_product_units ON other_product_units.id = prod.other_product_unit_id
                  LEFT JOIN transactions ON transactions.other_product_id = prod.id 
                        AND transactions.is_deleted = 0
                  GROUP BY prod.id, prod.name, other_product_units.name";

                  $data = DB::select(DB::raw($qry_str));
                  $response[] = [
                        "warehouse_name" => $warehouse->name,
                        "data" => $data
                  ];
            }
            return $response;
      }

      // Financial Report
      public function financialReport($obj)
      {
            $wh = [];
            if (isset($obj['supplier_id']) && $obj['supplier_id'] != 0 && $obj['supplier_id'] != "") {
                  $wh[] = ['journal_entries.supplier_id', '=', $obj['supplier_id']];
            }
            if (isset($obj['customer_id']) && $obj['customer_id'] != 0 && $obj['customer_id'] != "") {
                  $wh[] = ['journal_entries.customer_id', '=', $obj['customer_id']];
            }
            if (isset($obj['currency']) && $obj['currency'] != "") {
                  $wh[] = ['journal_entry_details.currency', '=', $obj['currency']];
            }
            if (isset($obj['account_id']) && $obj['account_id'] != 0 && $obj['account_id'] != "") {
                  $journal_entry = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->where('journal_entries.date_post', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
                        ->join('accounts', 'journal_entry_details.account_id', '=', 'accounts.id')
                        ->whereIn('journal_entry_details.account_id', $obj['account_id'])
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->groupBy('journal_entry_details.account_id', 'accounts.name', 'accounts.code')
                        ->selectRaw('
                              journal_entry_details.account_id,
                              accounts.code,
                              accounts.name,
                              SUM(journal_entry_details.debit) as total_debit,
                              SUM(journal_entry_details.credit) as total_credit,
                              (SUM(journal_entry_details.debit) - SUM(journal_entry_details.credit)) as balance
                        ')
                        ->orderBy('accounts.code', 'ASC')
                        ->get();
            } else {
                  $journal_entry = $this->model_journal_entry->getModel()::with(['supplier_name', 'customer_name', 'journal_name'])
                        ->where('journal_entries.date_post', '>=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['start_date']))))
                        ->where('journal_entries.date_post', '<=', date("Y-m-d", strtotime(str_replace('/', '-', $obj['end_date']))))
                        ->join('journal_entry_details', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
                        ->join('accounts', 'journal_entry_details.account_id', '=', 'accounts.id')
                        ->where($wh)
                        ->where('journal_entries.is_deleted', 0)
                        ->groupBy('journal_entry_details.account_id', 'accounts.name', 'accounts.code')
                        ->selectRaw('
                              journal_entry_details.account_id,
                              accounts.code,
                              accounts.name,
                              SUM(journal_entry_details.debit) as total_debit,
                              SUM(journal_entry_details.credit) as total_credit,
                              (SUM(journal_entry_details.debit) - SUM(journal_entry_details.credit)) as balance
                        ')
                        ->orderBy('accounts.code', 'ASC')
                        ->get();
            }
            return $journal_entry;
      }
}

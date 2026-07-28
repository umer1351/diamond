<?php

namespace App\Services\Concrete;

use App\Models\FinishProduct;
use App\Models\GoldImpurityPurchase;
use App\Models\JobPurchase;
use App\Models\JobTask;
use App\Models\OtherPurchase;
use App\Models\OtherSale;
use App\Models\PurchaseOrder;
use App\Models\RattiKaat;
use App\Models\Sale;
use App\Models\SaleOrder;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CommonService
{
      protected $model_ratti_kaat;
      protected $model_finish_product;

      protected $ratti_kaat_service;
      protected $job_purchase_service;
      protected $gold_impurity_purchase_service;
      public function __construct()
      {
            // set the model
            $this->model_ratti_kaat = new Repository(new RattiKaat);
            $this->model_finish_product = new Repository(new FinishProduct);

            //services
      }

      public function generateRattiKaatNo()
      {
            $ratti_kaat = RattiKaat::orderby('id', 'desc')->first();

            if (!$ratti_kaat) {
                  return "RK-" . date('dmY') . "-0001";
            }

            $mystring = $ratti_kaat->ratti_kaat_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "RK-" . date('dmY') . "-" . $mystring;
      }

      // Other purchase no
      public function generateOtherPurchaseNo()
      {
            $other_purchase = OtherPurchase::orderby('id', 'desc')->first();

            if (!$other_purchase) {
                  return "OPO-" . date('dmY') . "-0001";
            }

            $mystring = $other_purchase->other_purchase_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "OPO-" . date('dmY') . "-" . $mystring;
      }

      public function generateSaleNo()
      {
            $sale = Sale::orderby('id', 'desc')->first();

            if (!$sale) {
                  return "SL-" . date('dmY') . "-0001";
            }

            $mystring = $sale->sale_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "SL-" . date('dmY') . "-" . $mystring;
      }

      public function generateOtherSaleNo()
      {
            $other_sale = OtherSale::orderby('id', 'desc')->first();

            if (!$other_sale) {
                  return "OSL-" . date('dmY') . "-0001";
            }

            $mystring = $other_sale->other_sale_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "OSL-" . date('dmY') . "-" . $mystring;
      }

      public function generateFinishProductTagNo($prefix)
      {
            do {
                  $randomNumber = $prefix . mt_rand(10000, 99999);

                  $exists = DB::table('finish_products')->where('tag_no', $randomNumber)->exists();
            } while ($exists);

            return $randomNumber;
      }
      public function generateParentFinishProductTagNo()
      {
            do {
                  $randomNumber = 'PT' . mt_rand(10000, 99999);

                  $exists = DB::table('finish_products')->where('tag_no', $randomNumber)->exists();
            } while ($exists);

            return $randomNumber;
      }

      //Sale Order
      public function generateSaleOrderNo()
      {
            $sale_order = SaleOrder::orderby('id', 'desc')->first();

            if (!$sale_order) {
                  return "SO-" . date('dmY') . "-0001";
            }

            $mystring = $sale_order->sale_order_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "SO-" . date('dmY') . "-" . $mystring;
      }

      //Purchase Order
      public function generatePurchaseOrderNo()
      {
            $purchase_order = PurchaseOrder::orderby('id', 'desc')->first();

            if (!$purchase_order) {
                  return "POO-" . date('dmY') . "-0001";
            }

            $mystring = $purchase_order->purchase_order_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "POO-" . date('dmY') . "-" . $mystring;
      }

      //Job Task
      public function generateJobTaskNo()
      {
            $job_task = JobTask::orderby('id', 'desc')->first();

            if (!$job_task) {
                  return "JT-" . date('dmY') . "-0001";
            }

            $mystring = $job_task->job_task_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "JT-" . date('dmY') . "-" . $mystring;
      }

      // job purchase
      public function generateJobPurchaseNo()
      {
            $job_purchase = JobPurchase::orderby('id', 'desc')->first();

            if (!$job_purchase) {
                  return "JP-" . date('dmY') . "-0001";
            }

            $mystring = $job_purchase->job_purchase_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "JP-" . date('dmY') . "-" . $mystring;
      }

      // gold impurity
      public function generateGoldImpurityNo()
      {
            $gold_impurity = GoldImpurityPurchase::orderby('id', 'desc')->first();

            if (!$gold_impurity) {
                  return "GIP-" . date('dmY') . "-0001";
            }

            $mystring = $gold_impurity->gold_impurity_purchase_no;

            $mystring = substr($mystring, strrpos($mystring, "-") + 1);

            $mystring += 1;

            $lenZero = "";

            for ($i = 1; $i <= 4  - strlen($mystring); $i += 1) {
                  $lenZero = "0" . $lenZero;
            }

            $mystring = $lenZero . $mystring;

            return "GIP-" . date('dmY') . "-" . $mystring;
      }

      // get stock
      public function getOtherProductStockWithWarehouse($other_product_id, $warehouse_id, $start_date, $end_date)
      {
            $stock = 0.00;
            $wh = [];
            if (!empty($start_date) && !empty($end_date)) {
                  $wh[] = ['date', '>=', $start_date];
                  $wh[] = ['date', '<=', $end_date];
            }
            if (!empty($start_date) && empty($end_date)) {
                  $wh[] = ['date', '<=', $start_date];
            }
            $stock = DB::table('transactions')
                  ->select(DB::raw("SUM(
            CASE
                WHEN type IN (0) THEN qty
                WHEN type IN (1) THEN -qty
                WHEN type = 2 AND stock_taking_link_id IS NULL THEN
                    CASE
                        WHEN qty >= 0 THEN -qty
                        ELSE ABS(qty)
                    END
                WHEN type = 2 AND stock_taking_link_id > 0 THEN
                    CASE
                        WHEN qty >= 0 THEN qty
                        ELSE -ABS(qty)
                    END
                ELSE 0
            END
        ) AS stock
    "))
                  ->where('warehouse_id', $warehouse_id)
                  ->where('other_product_id', $other_product_id)
                  ->where('is_deleted', 0)
                  ->where($wh)
                  ->first();

            return number_format((float)($stock->stock ?? 0), 2, '.', '');
      }


      public function getOtherProductUnitPrice($other_product_id, $warehouse_id, $start_date, $end_date, $restaurant_id = null)
      {

            // Initialize the base query
            $query = "
        SELECT SUM(unit_price * qty)/SUM(qty) AS unit_price
        FROM transactions
        WHERE transactions.other_product_id = $other_product_id
        AND transactions.is_deleted = 0
        AND transactions.type IN (0, 2, 3)
        AND transactions.unit_price > 0
        AND transactions.qty > 0
    ";

            // Date filters
            if (!empty($start_date) && !empty($end_date)) {
                  $query .= " AND DATE(transactions.date) BETWEEN '$start_date' AND '$end_date'";
            } elseif (!empty($start_date)) {
                  $query .= " AND DATE(transactions.date) <= '$start_date'";
            }

            // Warehouse filter
            if (!empty($warehouse_id) && $warehouse_id != 0) {
                  $query .= " AND transactions.warehouse_id = $warehouse_id";
            }

            // Execute the raw query with bound parameters
            $result = DB::select(DB::raw($query));

            // Return the calculated average unit price or 0 if no result is found
            return round((float)$result[0]->unit_price ?? 0, 2);
      }
}

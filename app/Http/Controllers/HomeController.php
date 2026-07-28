<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $result = DB::select('CALL GetSalesAndCustomerSummary()');
            $data = $result[0] ?? (object) [];
        } catch (\Throwable $e) {
            $data = (object) [
                'today_customers' => DB::table('customers')->count(),
                'total_customers' => DB::table('customers')->count(),
                'today_sales' => DB::table('sales')->sum('total'),
                'yesterday_sales' => 0,
                'weekly_sales' => DB::table('sales')->sum('total'),
                'monthly_sales' => DB::table('sales')->sum('total'),
                'yearly_sales' => DB::table('sales')->sum('total'),
                'uncompleted_sale_orders' => 0,
                'today_delivery_sale_orders' => 0,
                'all_sale_orders' => DB::table('sale_orders')->count(),
            ];
        }

        try {
            $monthly_sale = DB::select("SELECT 
                DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -seq.seq MONTH), '%M') AS month_name,
                DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -seq.seq MONTH), '%Y') AS sale_year,
                COALESCE(SUM(s.total), 0) AS total_sales
            FROM (
                SELECT 0 AS seq UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
                UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
                UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                UNION ALL SELECT 10 UNION ALL SELECT 11
            ) AS seq
            LEFT JOIN sales s ON DATE_FORMAT(s.sale_date, '%Y-%m') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -seq.seq MONTH), '%Y-%m')
            LEFT JOIN sale_details sd ON sd.sale_id = s.id
            GROUP BY seq.seq
            ORDER BY seq.seq DESC
        ");
        } catch (\Throwable $e) {
            $monthly_sale = collect();
        }

        try {
            $highest_products = DB::select('SELECT 
                p.id AS product_id,
                p.name AS product_name,
                SUM(sd.total_amount) AS total_sales
             FROM sale_details sd
             JOIN sales s ON sd.sale_id = s.id
             JOIN products p ON sd.product_id = p.id
             WHERE s.is_deleted = 0
             GROUP BY p.id,p.name
             ORDER BY total_sales DESC
             LIMIT 5');
        } catch (\Throwable $e) {
            $highest_products = [];
        }

        $month = [];
        $sales = [];
        foreach ($monthly_sale as $item) {
            $month[] = (string)$item->month_name;
            $sales[] = (int)$item->total_sales;
        }
        $month = json_encode($month);
        $sales = json_encode($sales);
        return view('home', compact('data', 'month', 'sales', 'highest_products'));
    }
}

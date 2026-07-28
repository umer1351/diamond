<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGetSalesAndCustomerSummaryProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE PROCEDURE GetSalesAndCustomerSummary()
BEGIN
    -- Customers
    SELECT 
        (SELECT COUNT(*) FROM customers) AS total_customers,
        (SELECT COUNT(*) FROM customers WHERE DATE(created_at) = CURDATE()) AS today_customers,

    -- Sales amounts with child records
        (SELECT 
            SUM(CASE WHEN DATE(s.sale_date) = CURDATE() THEN s.total ELSE 0 END) 
         FROM sales s
         LEFT JOIN sale_details sd ON sd.sale_id = s.id
         WHERE s.is_deleted = 0 AND sd.sale_id IS NOT NULL) AS today_sales,

        (SELECT 
            SUM(CASE WHEN DATE(s.sale_date) = CURDATE() - INTERVAL 1 DAY THEN s.total ELSE 0 END)
         FROM sales s
         LEFT JOIN sale_details sd ON sd.sale_id = s.id
         WHERE s.is_deleted = 0 AND sd.sale_id IS NOT NULL) AS yesterday_sales,

        (SELECT 
            SUM(CASE WHEN WEEK(s.sale_date) = WEEK(CURDATE()) THEN s.total ELSE 0 END)
         FROM sales s
         LEFT JOIN sale_details sd ON sd.sale_id = s.id
         WHERE s.is_deleted = 0 AND sd.sale_id IS NOT NULL) AS weekly_sales,

        (SELECT 
            SUM(CASE WHEN MONTH(s.sale_date) = MONTH(CURDATE()) THEN s.total ELSE 0 END)
         FROM sales s
         LEFT JOIN sale_details sd ON sd.sale_id = s.id
         WHERE s.is_deleted = 0 AND sd.sale_id IS NOT NULL) AS monthly_sales,

        (SELECT 
            SUM(CASE WHEN YEAR(s.sale_date) = YEAR(CURDATE()) THEN s.total ELSE 0 END)
         FROM sales s
         LEFT JOIN sale_details sd ON sd.sale_id = s.id
         WHERE s.is_deleted = 0 AND sd.sale_id IS NOT NULL) AS yearly_sales,

    -- Sale orders with child records
        (SELECT COUNT(*) 
         FROM sale_orders so
         LEFT JOIN sale_order_details soi ON soi.sale_order_id = so.id
         WHERE DATE(so.sale_order_date) = CURDATE() AND soi.sale_order_id IS NOT NULL) AS today_sale_orders,

        (SELECT COUNT(*) 
         FROM sale_orders so
         LEFT JOIN sale_order_details soi ON soi.sale_order_id = so.id
         WHERE so.is_complete = 0 AND soi.sale_order_id IS NOT NULL) AS uncompleted_sale_orders,

        (SELECT COUNT(*) 
         FROM sale_orders so
         LEFT JOIN sale_order_details soi ON soi.sale_order_id = so.id
         WHERE DATE(so.delivery_date) = CURDATE() AND soi.sale_order_id IS NOT NULL) AS today_delivery_sale_orders,

        (SELECT COUNT(*) 
         FROM sale_orders so
         LEFT JOIN sale_order_details soi ON soi.sale_order_id = so.id
         WHERE soi.sale_order_id IS NOT NULL) AS all_sale_orders;
END;
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS GetSalesAndCustomerSummary");
    }
}

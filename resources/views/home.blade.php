@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/vendor/apexcharts.css') }}">
@endsection
@section('content')
@php
    $isRtl = app()->getLocale() === 'ar';
    $labels = [
        'today_customers' => $isRtl ? 'عملاء اليوم' : 'Today Customers',
        'all_customers' => $isRtl ? 'كل العملاء' : 'All Customers',
        'today_sales' => $isRtl ? 'مبيعات اليوم' : 'Today Sales',
        'yesterday_sales' => $isRtl ? 'مبيعات الأمس' : 'Yesterday Sales',
        'weekly_sales' => $isRtl ? 'مبيعات الأسبوع' : 'Weekly Sales',
        'monthly_sales' => $isRtl ? 'مبيعات الشهر' : 'Monthly Sales',
        'yearly_sales' => $isRtl ? 'مبيعات السنة' : 'Yearly Sales',
        'today_sale_orders' => $isRtl ? 'أوامر البيع اليوم' : 'Today Sale Orders',
        'process_sale_orders' => $isRtl ? 'أوامر البيع قيد المعالجة' : 'Process Sale Orders',
        'delivery_sale_orders' => $isRtl ? 'أوامر البيع للتسليم' : 'Delivery Sale Orders',
        'all_sale_orders' => $isRtl ? 'كل أوامر البيع' : 'All Sale Orders',
        'this_year_sales' => $isRtl ? 'مبيعات هذا العام' : 'This Year Sales',
        'sales_by_month' => $isRtl ? 'المبيعات حسب الشهر' : 'Sales by Month',
        'month_sales' => $isRtl ? 'مبيعات الشهر' : 'Month Sales',
        'week_sales' => $isRtl ? 'مبيعات الأسبوع' : 'Week Sales',
        'top_selling_products' => $isRtl ? 'أعلى المنتجات مبيعًا' : 'Top Selling Products',
    ];
    $salesSeries = json_decode($sales, true) ?: [];
    $salesMonths = json_decode($month, true) ?: [];
@endphp
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1 class="mr-2">{{ __('app.dashboard') }}</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Add-User"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['today_customers'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->today_customers ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Add-User"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['all_customers'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->total_customers ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Financial"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['today_sales'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->today_sales ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Financial"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['yesterday_sales'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->yesterday_sales ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Financial"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['weekly_sales'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->weekly_sales ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Financial"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['monthly_sales'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->monthly_sales ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Financial"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['yearly_sales'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->yearly_sales ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['today_sale_orders'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->yearly_sales ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['process_sale_orders'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->uncompleted_sale_orders ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['delivery_sale_orders'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->today_delivery_sale_orders ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center"><i class="i-Checkout-Basket"></i>
                    <div class="content" style="margin:0px;align-items: center; max-width: 100%; width: 100%;">
                        <p class="text-muted mt-2 mb-0">{{ $labels['all_sale_orders'] }}</p>
                        <p class="text-primary text-24 line-height-1 mb-2">{{ number_format($data->all_sale_orders ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title">{{ $labels['this_year_sales'] }}</div>
                    <div id="barChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title">{{ $labels['sales_by_month'] }}</div>
                    <div id="echartPie" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card card-chart-bottom o-hidden mb-4">
                        <div class="card-body">
                            <div class="text-muted">{{ $labels['month_sales'] }}</div>
                            <p class="mb-4 text-primary text-24">{{ $data->monthly_sales }}</p>
                        </div>
                        <div id="echart1" style="height: 260px;"></div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card card-chart-bottom o-hidden mb-4">
                        <div class="card-body">
                            <div class="text-muted">{{ $labels['week_sales'] }}</div>
                            <p class="mb-4 text-warning text-24">{{ $data->weekly_sales ?? 0 }}</p>
                        </div>
                        <div id="echart2" style="height: 260px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title">{{ $labels['top_selling_products'] }}</div>
                    @foreach($highest_products as $item)
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-3">
                            <div class="flex-grow-1">
                                <h5><a href="#">{{ $item->product_name ?? '' }}</a></h5>
                                <p class="text-small text-danger m-0">{{ number_format($item->total_sales ?? 0, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/vendor/apexcharts.dataseries.js') }}"></script>
<script src="{{ asset('assets/js/vendor/apexcharts.min.js') }}"></script>
<script>
    barChart();
    paiChart();

    function barChart() {
        var options = {
            chart: { type: 'bar', height: 350 },
            series: [{
                name: '{{ $isRtl ? "المبيعات" : "Sales" }}',
                data: @json($salesSeries)
            }],
            xaxis: {
                categories: @json($salesMonths)
            }
        };

        var chart = new ApexCharts(document.querySelector("#barChart"), options);
        chart.render();
    }

    function paiChart() {
        var options = {
            chart: { type: 'donut', height: 350 },
            series: @json($salesSeries),
            labels: @json($salesMonths),
            legend: { position: 'bottom' }
        };

        var chart = new ApexCharts(document.querySelector("#echartPie"), options);
        chart.render();
    }
</script>
@endsection

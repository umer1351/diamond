<div class="side-nav">
    <div class="main-menu">
        <ul class="metismenu" id="menu">
            @can('dashboard_access')
            <li class="Ul_li--hover"><a class="{{ Request::is('home') ? 'sidebar_active' : '' }}"
                    href="{{ url('home') }}"><i class="fa fa-home mr-2 text-muted" style="font-size:20px;"></i><span
                        class="item-name text-15 text-muted">{{ __('app.dashboard') }}</span></a>
            </li>
            @endcan
            @can('user_management_access')
            <li
                class="Ul_li--hover {{ Request::is('permissions*') || Request::is('roles*') || Request::is('users*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-users text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.user_management') }}</span></a>
                <ul class="mm-collapse">
                    @can('permissions_access')
                    <li class="item-name"><a class="{{ Request::is('permissions*') ? 'sidebar_active' : '' }}"
                            href="{{ url('permissions') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.permissions') }}</span></a></li>
                    @endcan

                    @can('roles_access')
                    <li class="item-name"><a class="{{ Request::is('roles*') ? 'sidebar_active' : '' }}"
                            href="{{ url('roles') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.roles') }}</span></a></li>
                    @endcan

                    @can('users_access')
                    <li class="item-name"><a class="{{ Request::is('users*') ? 'sidebar_active' : '' }}"
                            href="{{ url('users') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.users') }}</span></a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('customers_access')
            <li class="Ul_li--hover {{ Request::is('customers*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-user text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.customers') }}</span></a>
                <ul class="mm-collapse">
                    @can('customers_access')
                    <li class="item-name"><a class="{{ Request::is('customers*') ? 'sidebar_active' : '' }}"
                            href="{{ url('customers') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.customers') }}</span></a></li>
                    @endcan
                    @can('customer_payment_access')
                    <li class="item-name"><a class="{{ Request::is('customer-payment*') ? 'sidebar_active' : '' }}"
                            href="{{ url('customer-payment') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.customer_payment') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('inventory_access')
            <li class="Ul_li--hover {{ Request::is('warehouses*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-houzz text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.inventory') }}</span></a>
                <ul class="mm-collapse">
                    @can('tagging_product_access')
                    <li class="item-name"><a class="{{ Request::is('finish-product*') ? 'sidebar_active' : '' }}"
                            href="{{ url('finish-product') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.tagging_products') }}</span></a></li>
                    @endcan
                    @can('products_access')
                    <li class="item-name"><a class="{{ Request::is('product-categories*') ? 'sidebar_active' : '' }}"
                            href="{{ url('product-categories') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">Categories</span></a></li>
                    <li class="item-name"><a class="{{ Request::is('products*') ? 'sidebar_active' : '' }}"
                            href="{{ url('products') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">Finished Products</span></a></li>
                    @endcan
                    @can('other_product_access')
                    <li class="item-name"><a class="{{ Request::is('other-product*') ? 'sidebar_active' : '' }}"
                            href="{{ url('other-product') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.other_products') }}</span></a></li>
                    @endcan
                    @can('warehouses_access')
                    <li class="item-name"><a class="{{ Request::is('warehouses*') ? 'sidebar_active' : '' }}"
                            href="{{ url('warehouses') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.warehouses') }}</span></a></li>
                    @endcan
                    @can('suppliers_access')
                    <li class="item-name"><a class="{{ Request::is('suppliers*') ? 'sidebar_active' : '' }}"
                            href="{{ url('suppliers') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.suppliers') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('stock_access')
            <li class="Ul_li--hover {{ (Request::is('stock') || Request::is('stock-taking*') || Request::is('transaction*') ) ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-line-chart text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.stock') }}</span></a>
                <ul class="mm-collapse">
                    @can('stock_access')
                    <li class="item-name"><a class="{{ Request::is('stock') ? 'sidebar_active' : '' }}"
                            href="{{ url('stock') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.stock') }}</span></a></li>
                    @endcan
                    @can('stock_taking_access')
                    <li class="item-name"><a class="{{ Request::is('stock-taking*') ? 'sidebar_active' : '' }}"
                            href="{{ url('stock-taking') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.stock_taking') }}</span></a></li>
                    @endcan
                    @can('transaction_log_access')
                    <li class="item-name"><a class="{{ Request::is('transaction*') ? 'sidebar_active' : '' }}"
                            href="{{ url('transaction') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.transaction_logs') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('purchase_access')
            <li
                class="Ul_li--hover {{ Request::is('ratti-kaats*')|| Request::is('gold-impurity*') ||Request::is('job-purchase*') || Request::is('supplier-payment*') || Request::is('other-purchase*') || Request::is('purchase-order*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-houzz text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.purchase') }}</span></a>
                <ul class="mm-collapse">
                    @can('ratti_kaat_access')
                    <li class="item-name"><a class="{{ Request::is('ratti-kaats*') ? 'sidebar_active' : '' }}"
                            href="{{ url('ratti-kaats') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.ratti_kaat') }}</span></a></li>
                    @endcan
                    @can('other_purchase_access')
                    <li class="item-name"><a class="{{ Request::is('other-purchase*') ? 'sidebar_active' : '' }}"
                            href="{{ url('other-purchase') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.other_purchase') }}</span></a></li>
                    @endcan
                    @can('purchase_order_access')
                    <li class="item-name"><a class="{{ Request::is('purchase-order*') ? 'sidebar_active' : '' }}"
                            href="{{ url('purchase-order') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.purchase_order') }}</span></a></li>
                    @endcan
                    @can('job_purchase_access')
                    <li class="item-name"><a class="{{ Request::is('job-purchase*') ? 'sidebar_active' : '' }}"
                            href="{{ url('job-purchase') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.job_purchase') }}</span></a></li>
                    @endcan
                    @can('gold_impurity_access')
                    <li class="item-name"><a class="{{ Request::is('gold-impurity*') ? 'sidebar_active' : '' }}"
                            href="{{ url('gold-impurity') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.gold_impurity') }}</span></a></li>
                    @endcan
                    @can('supplier_payment_access')
                    <li class="item-name"><a class="{{ Request::is('supplier-payment*') ? 'sidebar_active' : '' }}"
                            href="{{ url('supplier-payment') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.supplier_payment') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('job_task_access')
            <li class="Ul_li--hover {{ Request::is('job-task*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-houzz text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.job_task') }}</span></a>
                <ul class="mm-collapse">
                    @can('job_task_access')
                    <li class="item-name"><a class="{{ Request::is('job-task*') ? 'sidebar_active' : '' }}"
                            href="{{ url('job-task') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.jobs') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('accounting_access')
            <li class="Ul_li--hover {{ Request::is('accounts*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-line-chart text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.accounting') }}</span></a>
                <ul class="mm-collapse">
                    @can('accounts_access')
                    <li class="item-name"><a class="{{ Request::is('accounts*') ? 'sidebar_active' : '' }}"
                            href="{{ url('accounts') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.chart_of_accounts') }}</span></a></li>
                    @endcan
                    @can('journals_access')
                    <li class="item-name"><a class="{{ Request::is('journals*') ? 'sidebar_active' : '' }}"
                            href="{{ url('journals') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.journals') }}</span></a></li>
                    @endcan
                    @can('journal_entries_access')
                    <li class="item-name"><a class="{{ Request::is('journal-entries*') ? 'sidebar_active' : '' }}"
                            href="{{ url('journal-entries') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.journal_entries') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('common_access')
            <li
                class="Ul_li--hover {{ Request::is('bead-type*') || Request::is('stone-category*') || Request::is('diamond-type*') || Request::is('diamond-color*') || Request::is('diamond-cut*') || Request::is('diamond-clarity*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-empire text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.commons') }}</span></a>
                <ul class="mm-collapse">
                    @can('bead_type_access')
                    <li class="item-name"><a class="{{ Request::is('bead-type*') ? 'sidebar_active' : '' }}"
                            href="{{ url('bead-type') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.bead_types') }}</span></a></li>
                    @endcan
                    @can('stone_category_access')
                    <li class="item-name"><a class="{{ Request::is('stone-category*') ? 'sidebar_active' : '' }}"
                            href="{{ url('stone-category') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.stone_category') }}</span></a></li>
                    @endcan
                    @can('diamond_type_access')
                    <li class="item-name"><a class="{{ Request::is('diamond-type*') ? 'sidebar_active' : '' }}"
                            href="{{ url('diamond-type') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.diamond_type') }}</span></a></li>
                    @endcan
                    @can('diamond_color_access')
                    <li class="item-name"><a class="{{ Request::is('diamond-color*') ? 'sidebar_active' : '' }}"
                            href="{{ url('diamond-color') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.diamond_color') }}</span></a></li>
                    @endcan
                    @can('diamond_cut_access')
                    <li class="item-name"><a class="{{ Request::is('diamond-cut*') ? 'sidebar_active' : '' }}"
                            href="{{ url('diamond-cut') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.diamond_cut') }}</span></a></li>
                    @endcan
                    @can('diamond_clarity_access')
                    <li class="item-name"><a class="{{ Request::is('diamond-clarity*') ? 'sidebar_active' : '' }}"
                            href="{{ url('diamond-clarity') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.diamond_clarity') }}</span></a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('sales_access')
            <li class="Ul_li--hover {{ (Request::is('sale*') || Request::is('other-sale*') || Request::is('sale-order*') )? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-empire text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.sales') }}</span></a>
                <ul class="mm-collapse">
                    @can('sale_access')
                    <li class="item-name"><a class="{{ Request::is('sale*') ? 'sidebar_active' : '' }}"
                            href="{{ url('sale') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.sales') }}</span></a></li>
                    @endcan
                    @can('other_sale_access')
                    <li class="item-name"><a class="{{ Request::is('other-sale*') ? 'sidebar_active' : '' }}"
                            href="{{ url('other-sale') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.other_sales') }}</span></a></li>
                    @endcan
                    @can('sale_order_access')
                    <li class="item-name"><a class="{{ Request::is('sale-order*') ? 'sidebar_active' : '' }}"
                            href="{{ url('sale-order') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.sale_order') }}</span></a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('gold_rate_access')
            <li class="Ul_li--hover {{ Request::is('gold-rate*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-houzz text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.gold_rate') }}</span></a>
                <ul class="mm-collapse">
                    @can('gold_chart_access')
                    <li class="item-name"><a class="{{ Request::is('gold-rate') ? 'sidebar_active' : '' }}"
                            href="{{ url('gold-rate') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.chart') }}</span></a></li>
                    @endcan
                    @can('gold_rate_log_access')
                    <li class="item-name"><a class="{{ Request::is('gold-rate/logs') ? 'sidebar_active' : '' }}"
                            href="{{ url('gold-rate/logs') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.logs') }}</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('report_access')
            <li class="Ul_li--hover {{ Request::is('reports*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-empire text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.reports') }}</span></a>
                <ul class="mm-collapse">
                    @can('ledger_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/ledger-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/ledger-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.ledger_report') }}</span></a></li>
                    @endcan
                    @can('tag_history_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/tag-history-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/tag-history-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.tag_history_report') }}</span></a></li>
                    @endcan
                    @can('profit_loss_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/profit-loss-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/profit-loss-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.profit_loss_report') }}</span></a></li>
                    @endcan
                    @can('stock_ledger_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/stock-ledger-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/stock-ledger-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.stock_ledger') }}</span></a></li>
                    @endcan

                    @can('product_ledger_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/product-ledger-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/product-ledger-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.product_ledger') }}</span></a></li>
                    @endcan

                    @can('customer_list_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/customer-list-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/customer-list-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.customer_list') }}</span></a></li>
                    @endcan
                    @can('product_consumption_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/product-consumption-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/product-consumption-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.product_consumption') }}</span></a></li>
                    @endcan
                    @can('financial_report')
                    <li class="item-name"><a
                            class="{{ Request::is('reports/financial-report*') ? 'sidebar_active' : '' }}"
                            href="{{ url('reports/financial-report') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">{{ __('app.financial_report') }}</span></a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('dollar_rate_access')
            <li class="Ul_li--hover {{ Request::is('dollar-rate*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-houzz text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.dollar_rate') }}</span></a>
                <ul class="mm-collapse">
                    @can('dollar_rate_log_access')
                    <li class="item-name"><a class="{{ Request::is('dollar-rate/logs') ? 'sidebar_active' : '' }}"
                            href="{{ url('dollar-rate/logs') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">Logs</span></a></li>
                    @endcan

                </ul>
            </li>
            @endcan
            @can('hrm_access')
            <li class="Ul_li--hover {{ Request::is('employees*') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="#"><i class="fa fa-empire text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">HRM</span></a>
                <ul class="mm-collapse">
                    @can('employees_access')
                    <li class="item-name"><a class="{{ Request::is('employees*') ? 'sidebar_active' : '' }}"
                            href="{{ url('employees') }}"><i class="nav-icon fa fa-circle"></i><span
                                class="item-name">Employee</span></a></li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('setting_access')
            <li class="Ul_li--hover"><a class="{{ Request::is('company-setting') ? 'sidebar_active' : '' }}"
                    href="{{ url('company-setting') }}"><i class="fa fa-cogs mr-2 text-muted" style="font-size:20px;"></i><span
                        class="item-name text-15 text-muted">{{ __('app.settings') }}</span></a>
            </li>
            @endcan
            <li class="Ul_li--hover"><a class="{{ Request::is('cms*') ? 'sidebar_active' : '' }}"
                    href="{{ url('cms') }}"><i class="fa fa-window-maximize mr-2 text-muted" style="font-size:20px;"></i><span
                        class="item-name text-15 text-muted">CMS Manager</span></a>
            </li>
            {{-- @can('logout_access') --}}
            <li class="Ul_li--hover"><a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();"><i
                        class="fa fa-sign-out text-20 mr-2 text-muted"></i><span
                        class="item-name text-15 text-muted">{{ __('app.logout') }}</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            {{-- @endcan --}}
        </ul>
    </div>
</div>

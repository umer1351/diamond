<!DOCTYPE html>
@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
@endphp
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name', 'ERP') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="{{ asset('assets/css/themes/lite-purple.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom-brand.css') }}" rel="stylesheet" />
    @if ($isRtl)
        <link href="{{ asset('assets/css/bootstrap-rtl.css') }}" rel="stylesheet" />
    @endif
    <link href="{{ asset('assets/css/plugins/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome-5.css') }}" />
    <link href="{{ asset('assets/css/plugins/metisMenu.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/datatables.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.1/sweetalert2.min.css"
        integrity="sha512-NvuRGlPf6cHpxQqBGnPe7fPoACpyrjhlSNeXVUY7BZAj1nNhuNpRBq3osC4yr2vswUEuHq2HtCsY2vfLNCndYA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @yield('css')
</head>

<body class="{{ $isRtl ? 'text-right' : 'text-left' }}">
    <!-- Pre Loader Strat  -->
    <div class='loadscreen' id="preloader">

        <div class="loader spinner spinner-primary">
        </div>
    </div>

    <!-- Pre Loader end  -->
    <div class="app-admin-wrap layout-sidebar-vertical sidebar-full {{ $isRtl ? 'rtl-layout' : 'ltr-layout' }}">
        <div class="sidebar-panel bg-white">
            <div class="gull-brand pr-3 text-center mt-3 mb-2 d-flex justify-content-center align-items-center">
                <img class="sidebar-brand-logo" src="{{ asset('assets/images/azure-fashion-logo.png') }}" alt="Azure Fashion">
                <div class="sidebar-compact-switch ml-auto"><span></span></div>
            </div>
            <!--  user -->
            <div class="scroll-nav ps ps--active-y" data-perfect-scrollbar="data-perfect-scrollbar"
                data-suppress-scroll-x="true">

                @include('layouts.sidebar')

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; height: 404px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 325px;"></div>
                </div>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; height: 404px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 325px;"></div>
                </div>
            </div>
            <!--  side-nav-close -->
        </div>
        <div class="switch-overlay"></div>
        <div class="main-content-wrap mobile-menu-content bg-off-white m-0">
            @include('layouts.header')

            @yield('content')

            <div class="sidebar-overlay open"></div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
            <div class="app-footer">
                <div class="row">
                    <div class="col-md-9">
                    </div>
                </div>
                <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
                    
                    <span class="flex-grow-1"></span>
                    <div class="d-flex align-items-center">
                        <img class="logo" src="{{ asset('assets/images/azure-fashion-logo.png') }}" alt="Azure Fashion" style="max-width:220px;height:auto;object-fit:contain;">
                        <div>
                            <p class="m-0">&copy; <span id="year"></span></p>
                            <p class="m-0">{{ __('app.all_rights_reserved') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- fotter end -->
        </div>
    </div><!-- ============ Search UI Start ============= -->
    <div class="search-ui">
        <div class="search-header">
            <img src="{{ asset('assets/images/azure-fashion-logo.png') }}" alt="Azure Fashion" class="logo" style="max-width:220px;height:auto;object-fit:contain;">
            <button class="search-close btn btn-icon bg-transparent float-right mt-2">
                <i class="i-Close-Window text-22 text-muted"></i>
            </button>
        </div>
        <input type="text" placeholder="{{ $isRtl ? 'اكتب هنا' : 'Type here' }}" class="search-input" autofocus>
        <div class="search-title">
            <span class="text-muted">{{ $isRtl ? 'نتائج البحث' : 'Search results' }}</span>
        </div>
        <div class="search-results list-horizontal">
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="{{ asset('assets/images/products/headphone-1.jpg') }}" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div
                            class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-danger">Sale</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="{{ asset('assets/images/products/headphone-2.jpg') }}" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div
                            class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="{{ asset('assets/images/products/headphone-3.jpg') }}" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div
                            class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-item col-md-12 p-0">
                <div class="card o-hidden flex-row mb-4 d-flex">
                    <div class="list-thumb d-flex">
                        <!-- TUMBNAIL -->
                        <img src="{{ asset('assets/images/products/headphone-4.jpg') }}" alt="">
                    </div>
                    <div class="flex-grow-1 pl-2 d-flex">
                        <div
                            class="card-body align-self-center d-flex flex-column justify-content-between align-items-lg-center flex-lg-row">
                            <!-- OTHER DATA -->
                            <a href="" class="w-40 w-sm-100">
                                <div class="item-title">Headphone 1</div>
                            </a>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">Gadget</p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100">$300
                                <del class="text-secondary">$400</del>
                            </p>
                            <p class="m-0 text-muted text-small w-15 w-sm-100 d-none d-lg-block item-badges">
                                <span class="badge badge-primary">New</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGINATION CONTROL -->
        <div class="col-md-12 mt-5 text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination d-inline-flex">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    @include('includes/change_gold_rate')
    @include('includes/change_dollar_rate')
    <!-- ============ Search UI End ============= -->
    <script>
        window.APP_LOCALE = @json($locale);
        window.APP_DIR = @json($isRtl ? 'rtl' : 'ltr');
        window.APP_I18N = {!! json_encode([
            'en' => [
                'create_new' => 'Create New',
                'edit' => 'Edit',
                'add_new' => 'Add New',
                'save' => 'Save',
                'update' => 'Update',
                'delete' => 'Delete',
                'close' => 'Close',
                'search' => 'Search',
                'actions' => 'Actions',
                'status' => 'Status',
                'name' => 'Name',
                'date' => 'Date',
                'number' => 'Number',
                'details' => 'Details',
                'create' => 'Create',
                'manage' => 'Manage',
            ],
            'ar' => [
                'create_new' => 'إنشاء جديد',
                'edit' => 'تعديل',
                'add_new' => 'إضافة جديد',
                'save' => 'حفظ',
                'update' => 'تحديث',
                'delete' => 'حذف',
                'close' => 'إغلاق',
                'search' => 'بحث',
                'actions' => 'الإجراءات',
                'status' => 'الحالة',
                'name' => 'الاسم',
                'date' => 'التاريخ',
                'number' => 'الرقم',
                'details' => 'التفاصيل',
                'create' => 'إنشاء',
                'manage' => 'إدارة',
            ],
        ], JSON_UNESCAPED_UNICODE) !!};
        window.t = function (key) {
            return (window.APP_I18N && window.APP_I18N[window.APP_LOCALE] && window.APP_I18N[window.APP_LOCALE][key]) || key;
        };
    </script>
    <script src="{{ asset('assets/js/plugins/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/tooltip.script.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/script.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/script_2.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/sidebar.large.script.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/layout-sidebar-vertical.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/echart.options.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/dashboard.v1.script.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts/customizer.script.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/vendor/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.script.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
    <script>
        function getCurrentYear() {
            return new Date().getFullYear(); // returns the year via local timing
        };

        document.getElementById("year").innerHTML = getCurrentYear();


        $("#ChangeGoldRate").click(function() {
            $("#changeGoldRateForm").trigger("reset");
            $("#changeGoldRateModel").modal("show");
        });

        $("#ChangeDollarRate").click(function() {
            $("#changeDollarRateForm").trigger("reset");
            $("#changeDollarRateModel").modal("show");
        });

        $('.tool_tip')
            .attr('data-toggle', 'tooltip')
            .attr('data-placement', 'top')
            .tooltip({
                trigger: 'manual'
            })
            .tooltip('show');

        function isNumberKey(evt) {
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>
    @yield('js')
</body>

</html>

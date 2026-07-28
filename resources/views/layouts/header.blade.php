@php
$gold_rate = GoldRate();
$dollar_rate = DollarRate();
@endphp
<header class="main-header bg-white d-flex justify-content-between p-2">
    <div class="header-toggle">
        <a href="{{ url('storefront') }}" class="d-flex align-items-center mr-3">
            <img src="{{ asset('assets/images/azure-fashion-logo.png') }}" alt="Azure Fashion" style="height:72px;width:auto;max-width:320px;object-fit:contain;">
        </a>
        <div class="menu-toggle mobile-menu-icon">
            <div></div>
            <div></div>
            <div></div>
        </div>
        @can('gold_rate_log_access')
        <div class="rate-pill">

            <h3 class="mr-1 font-weight-bold" data-toggle="tooltip" data-placement="top" title="{{isset($dollar_rate)?$gold_rate->created_at->format('d-m-Y g:i A'):''}}">AU: {{number_format($gold_rate->rate_tola??0,2)}}</h3>
            @can('gold_rate_log_create')
            <a href="javascript:void(0)" id="ChangeGoldRate" style="padding: 3px 5px 3px 5px;" class="btn-primary mr-2"><i
                    class="fa fa-refresh text-white"></i></a>
            @endcan
        </div>
        @endcan
        @can('dollar_rate_log_access')
        <div class="rate-pill">
            <h3 class="mr-1 font-weight-bold" data-toggle="tooltip" data-placement="top" title="{{isset($dollar_rate)?$dollar_rate->created_at->format('d-m-Y g:i A'):''}}">$: {{number_format($dollar_rate->rate??0,2)}}</h3>
            @can('dollar_rate_log_create')
            <a href="javascript:void(0)" id="ChangeDollarRate" style="padding: 3px 5px 3px 5px;" class="btn-primary mr-2"><i
                    class="fa fa-refresh text-white"></i></a>
            @endcan
        </div>
        @endcan
    </div>
    <div class="header-part-right">
        <div class="dropdown dropleft mr-2">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                {{ strtoupper(app()->getLocale()) }}
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">{{ __('app.english') }}</a>
                <a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">{{ __('app.arabic') }}</a>
            </div>
        </div>
        <!-- Full screen toggle--><i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen=""></i>
        <!-- Grid menu Dropdown-->
        <div class="dropdown dropleft"><i class="i-Safe-Box text-muted header-icon" id="dropdownMenuButton"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div class="menu-icon-grid"><a href="#"><i class="i-Shop-4"></i> Home</a><a href="#"><i
                            class="i-Library"></i> UI Kits</a><a href="#"><i class="i-Drop"></i> Apps</a><a
                        href="#"><i class="i-File-Clipboard-File--Text"></i> Forms</a><a href="#"><i
                            class="i-Checked-User"></i> Sessions</a><a href="#"><i class="i-Ambulance"></i>
                        Support</a></div>
            </div>
        </div>
    </div>
</header>

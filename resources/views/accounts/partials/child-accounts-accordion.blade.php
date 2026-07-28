@foreach ($accounts as $key => $account)
    <div id="accordionChildLeftIcon">
        <div class="card ul-card__v-space" style="margin: 10px 0;">
            <div class="card-header bg-info header-elements-inline">
                @if ($account['level']==2)
                    <h6 class="mb-0">
                        <a class="text-white">{{ $account['code'] }} {{ $account['name'] }}
                        </a>
                    </h6>
                @else
                    <h6 class="card-titleul-collapse__icon--size ul-collapse__left-icon  mb-0">
                        <a class="text-white" data-toggle="collapse"
                            href="#collapsible-item-nested-child1-{{ $account['id'] }}"
                            aria-expanded="true">{{ $account['code'] }} {{ $account['name'] }}
                        </a>
                    </h6>
                @endif

                <div class="list-icons ul-card__list--icon-font">

                    @can('accounts_edit')
                        <span class="fas fa-stack fa-1x">
                            <a style="float: right;" id="editParentAccount"
                                href="accounts/getAccountById/{{ $account['id'] }}">

                                <i class="fa fa-solid fa-circle fa-stack-2x"></i>
                                <i class=" fa fa-solid fa-edit fa-stack-1x fa-inverse"></i>
                            </a>

                        </span>
                        @endcan
                    @can('accounts_create')
                    @if ($account['level']!=2)
                            <span class="fas fa-stack fa-1x">
                                <a style="float: right;" data-parentid="{{ $account['id'] }}"
                                    data-account-type-id="{{ $account['account_type_id'] }}" class="openAddChildAccount"
                                    href="#">
                                    <i class="fa fa-solid fa-circle fa-stack-2x"></i>
                                    <i class=" fa fa-solid fa-plus fa-stack-1x fa-inverse"></i>
                                </a>
                            </span>
                    @endif
                    @endcan
                    
                    @can('accounts_delete')
                        <span class="fas fa-stack fa-1x">
                            <a style="float: right;" data-id="{{ $account['id'] }}" id="deleteChartAccount"
                                href="javascript:void(0)">
                                <i class="fa fa-solid fa-circle fa-stack-2x"></i>
                                <i class="fa fa-solid fa-trash fa-stack-1x fa-inverse"></i>
                            </a>
                        </span>
                        @endcan
                        
                    @can('accounts_status')
                    @if ($account['is_active'] == 1)
                        <label class="switch switch-primary ml-2 mt-2"><input type="checkbox" checked="checked"
                                id="is_active" data-id="{{ $account['id'] }}"><span class="slider"></span></label>
                    @else
                        <label class="switch switch-primary ml-2 mt-2"><input type="checkbox" id="is_active"
                                data-id="{{ $account['id'] }}"><span class="slider"></span></label>
                    @endif
                    @endcan
                </div>

            </div>
            <div class="collapse" id="collapsible-item-nested-child1-{{ $account['id'] }}"
                data-parent="#accordionChildLeftIcon">
                <div class="card-body">
                    @if ($account['childs'] != null)
                        @include('accounts.partials.child-account-accordion', [
                            'accounts' => $account['childs'],
                        ])
                    @else
                        <div class="text-center">
                            <b>There is no Child</b>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endforeach

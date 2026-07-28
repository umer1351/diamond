<div class="accordion" id="accordionLeftIcon">
    @foreach ($arr as $key => $parent)
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title ul-collapse__icon--size ul-collapse__left-icon  mb-0">
                    <a data-toggle="collapse" class="showchildren text-default collapsed" aria-expanded="false"
                        data-id="{{ $parent->id }}" href="#collapse-{{ $parent->id }}"> {{ $parent->code }}
                        {{ $parent->name }}</a>
                </h6>

                <div class="list-icons ul-card__list--icon-font">
                    
                    @can('accounts_edit')
                    <span class="fas fa-stack fa-1x">
                        <a style="float: right;" id="editParentAccount" href="accounts/getAccountById/{{ $parent->id }}">

                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class=" fa fa-edit fa-stack-1x fa-inverse"></i>
                        </a>

                    </span>
                    @endcan
                    
                    @can('accounts_create')
                    <span class="fas fa-stack fa-1x">
                        <a style="float: right;" data-parent-id="{{ $parent->id }}"
                            data-account-type-id="{{ $parent->account_type_id }}" class="openAddChildAccount"
                            href="#">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class=" fa fa-plus fa-stack-1x fa-inverse"></i>
                        </a>
                    </span>
                    @endcan
                    
                    @can('accounts_delete')
                    <span class="fas fa-stack fa-1x">
                        <a style="float: right;" data-id="{{ $parent->id }}" id="deleteChartAccount"
                            href="javascript:void(0)">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-trash fa-stack-1x fa-inverse"></i>
                        </a>
                    </span>
                    @endcan

                    
                    @can('accounts_status')
                    @if ($parent->is_active == 1)
                        <label class="switch switch-primary ml-2 mt-2"><input type="checkbox" checked="checked"
                                id="is_active" data-id="{{ $parent->id }}"><span class="slider"></span></label>
                    @else
                        <label class="switch switch-primary ml-2 mt-2"><input type="checkbox" id="is_active"
                                data-id="{{ $parent->id }}"><span class="slider"></span></label>
                    @endif
                    @endcan
                </div>
            </div>

            <div class="collapse" id="collapse-{{ $parent->id }}" data-parent="#accordionLeftIcon" data-chk="0">
                <div class="card-body">


                </div>
            </div>
        </div>
    @endforeach
</div>

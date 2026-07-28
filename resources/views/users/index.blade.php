@extends('layouts.master')
@section('content')

<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>{{ __('app.users') }}</h1>
        <ul>
            <li>List</li>
            <li>All</li>
        </ul>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="card text-left">
                <div class="card-header text-right bg-transparent">
                        <a class="btn btn-primary btn-md m-1" href="{{url('users/create')}}" id="createNewProject"><i
                                class="fa fa-plus text-white mr-2"></i> Add User</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="user_table" class="table table-striped display" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of col-->
    </div>
    <!-- end of row-->
</div>

@endsection
@section('js')
@include('includes.datatable', [
        'columns' => "
         {data: 'name' , name: 'name'},
         {data: 'email' , name: 'email'},
         {data: 'role' , name: 'role'},
         {data: 'supplier' , name: 'supplier'},
        {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'users/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'user_table',
        'variable' => 'user_table',
    ])
@endsection

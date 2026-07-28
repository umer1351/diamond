@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Roles</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <div class="card text-left">
                    <div class="card-header text-right bg-transparent">
                        <a class="btn btn-primary btn-md m-1" href="{{ url('roles/create') }}" id="createNewProject"><i
                                class="fa fa-plus text-white mr-2"></i> Add Role</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="role_table" class="table table-striped display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Role Name</th>
                                        <th scope="col">Permissions</th>
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
             {data: 'permissions' , name: 'permissions' , 'sortable': false , searchable: false},
            {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'roles/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'role_table',
        'variable' => 'role_table',
    ])
@endsection

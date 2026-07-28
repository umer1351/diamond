@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Permission</h1>
            @if(isset($permission))
            <ul>
                <li>Update</li>
                <li>Edit</li>
            </ul>
            @else
            <ul>
                <li>Create</li>
                <li>Add</li>
            </ul>
            @endif
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <form action="{{ url('permissions/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{isset($permission)?$permission->id:''}}" />
                                <div class="col-md-6 form-group mb-3">
                                    <label for="firstName1">Permission Name<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="text" name="name" value="{{isset($permission)?$permission->name:old('name')}}"
                                       maxlength="50" placeholder="Enter permission name" required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <div class="card-footer">

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ url('permissions') }}" class="btn btn-danger">Cancel</a>
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- end of main-content -->
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Warehouse</h1>
            @if(isset($warehouse))
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
                    <form action="{{ url('warehouses/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{isset($warehouse)?$warehouse->id:''}}" />
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">Warehouse Name<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="text" name="name" value="{{isset($warehouse)?$warehouse->name:old('name')}}"
                                       maxlength="50" placeholder="Enter warehouse name" required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email">Warehouse Email</label>
                                    <input class="form-control" type="email" name="email" value="{{isset($warehouse)?$warehouse->email:old('email')}}"
                                       maxlength="50" placeholder="Enter warehouse email" />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="phone">Warehouse phone</label>
                                    <input class="form-control" type="text" name="phone" value="{{isset($warehouse)?$warehouse->phone:old('phone')}}"
                                       maxlength="11" placeholder="Enter warehouse phone" />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="address">Warehouse address</label>
                                    <input class="form-control" type="text" name="address" value="{{isset($warehouse)?$warehouse->address:old('address')}}"
                                       maxlength="70" placeholder="Enter warehouse address" />
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ url('warehouses') }}" class="btn btn-danger">Cancel</a>
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

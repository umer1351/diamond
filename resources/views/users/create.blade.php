@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>User</h1>
            @if(isset($user))
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
                    <form action="{{ url('users/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{isset($user)?$user->id:''}}" />
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">Full Name<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="text" name="name" value="{{isset($user)?$user->name:old('name')}}"
                                       maxlength="50" placeholder="Enter User name" required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">User EMail<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="email" name="email" value="{{isset($user)?$user->email:old('email')}}"
                                       maxlength="50" placeholder="Enter User EMail" required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">Password<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="password" name="password" value=""
                                       maxlength="16" placeholder="Enter User Password" required />
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">Confirm Password<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="password" name="password_confirmation" value=""
                                       maxlength="16" placeholder="Renter Password" required />
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="role">Role<span class="text-danger">*</span> </label>
                                    <select class="form-control select2" name="role" id="role" required style="width: 100%;">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>{{ $role->name??'' }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3" id="supplier_div">
                                    <label for="supplier_id">Supplier/Karigar<span class="text-danger">*</span> </label>
                                    <select class="form-control select2" name="supplier_id" id="supplier_id" required style="width: 100%;">
                                        @foreach($suppliers as $item)
                                            <option value="{{ $item->id }}" {{ (isset($user) && $user->supplier_id==$item->id) ? 'selected' : '' }}>{{ $item->name??'' }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <div class="card-footer">

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ url('users') }}" class="btn btn-danger">Cancel</a>
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
@section('js')
<script>
    $(document).ready(function() {
            $('#role').select2();
            $('#supplier_id').select2();
            $("#supplier_div").hide();
        });
    $("#role").on("change",function(e){
        if($('#role').find(':selected').val()=='Supplier/Karigar User'){
            $("#supplier_div").show();
        }
    });
</script>
@endsection

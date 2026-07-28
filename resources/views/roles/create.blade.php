@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Role</h1>
        @if(isset($role))
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
                <form action="{{ url('roles/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{isset($role)?$role->id:''}}" />
                            <div class="col-md-6 form-group mb-3">
                                <label for="name">Role Name<span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" name="name" value="{{isset($role)?$role->name:old('name')}}"
                                    maxlength="50" placeholder="Enter Role name" required />
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="permissions">Permissions<span class="text-danger">*</span>
                                    <button id="selectAll" type="button" class="btn-success" style="border:1px solid #000;">Select All</button>
                                    <button id="deselectAll" type="button" class="btn-danger" style="border:1px solid #000;">Deselect All</button>
                                </label>
                                <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple required style="height: 100px;">
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}" {{ (isset($role) && $role->hasPermissionTo($permission->name)) ? 'selected' : '' }}>{{ $permission->name??'' }}</option>
                                    @endforeach
                                </select>
                                @error('permissions')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">

                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('roles') }}" class="btn btn-danger">Cancel</a>
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
        $('#permissions').select2();

        // Select All Button
        $('#selectAll').on('click', function() {
            let allValues = [];
            $('#permissions option').each(function() {
                allValues.push($(this).val()); // Collect all values
            });
            $('#permissions').val(allValues).trigger('change'); // Set values and trigger change
        });

        // Deselect All Button
        $('#deselectAll').on('click', function() {
            $('#permissions').val(null).trigger('change'); // Clear all selections
        });
    });
</script>
@endsection
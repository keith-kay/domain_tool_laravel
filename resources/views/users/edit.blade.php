<!-- Edit Form View (edit.blade.php) -->
@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Edit User</h3>
        <hr class="my-4">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="post" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control" id="name" name="name" placeholder="Enter User Name" value="{{ old('name', $user->name) }}">
                    <span class="text-danger">@error('name') {{$message}} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter User Password" value="{{ old('password', $user->password) }}">
                    <span class="text-danger">@error('password') {{$message}} @enderror</span>
                </div>
                <div class="col-md-8">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" required class="form-control" id="email" name="email" placeholder="Enter User Email" value="{{ old('email', $user->email) }}">
                    <span class="text-danger">@error('email') {{$message}} @enderror</span>
                </div>
                <br>
            </div>
            <div class="form-group my-3">
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="is_user" class="form-label mx-2">User</label>
                    <input type="checkbox" id="is_user" name="is_user" value="1" {{ $user->is_user ? 'checked' : '' }}>
                </div>
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="is_admin" class="form-label mx-2">Admin</label>
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                </div>
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="is_active" class="form-label mx-2">Active</label>
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-database" aria-hidden="true"></i> Submit</button>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to list
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
    @parent
    <script>
    $(document).ready(function() {
        $('#is_user').change(function() {
            if (this.checked) {
                $('#is_admin').prop('checked', false).prop('disabled', true);
            } else {
                $('#is_admin').prop('disabled', false);
            }
        });

        $('#is_admin').change(function() {
            if (this.checked) {
                $('#is_user').prop('checked', false).prop('disabled', true);
            } else {
                $('#is_user').prop('disabled', false);
            }
        });

        // Remove the event listener for the Active checkbox
        $('#active').change(function() {
            if (!this.checked) {
                this.checked = true;
            }
        });
    });
    </script>
@stop


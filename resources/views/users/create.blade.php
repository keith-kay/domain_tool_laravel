@extends('adminlte::page')

@section('title', 'Add User')

@section('content_header')
    <h1>Add User</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Add User</h3>
        <hr class="my-4">
        <form action="" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control" id="name" name="name" placeholder="Enter User Name" value="{{ old('name') }}">
                    <span class="text-danger">@error('name') {{$message}} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Password</label>
                    <input type="password" required class="form-control" id="email" name="password" placeholder="Enter User Password" value="{{ old('password') }}">
                    <span class="text-danger">@error('email') {{$message}} @enderror</span>
                </div>
                <div class="col-md-8">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" required class="form-control" id="email" name="email" placeholder="Enter User Email" value="{{ old('email') }}">
                    <span class="text-danger">@error('email') {{$message}} @enderror</span>
                </div>
                <br>
            </div>
            <div class="form-group my-3">
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="email" class="form-label mx-2">User</label>
                    <input type="radio" required id="user" name="is_user">
                </div>
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="email" class="form-label mx-2">Admin</label>
                    <input type="radio" required id="admin" name="is_admin">
                </div>
                <div class="col-md-2" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                    <label for="email" class="form-label mx-2">Active</label>
                    <input type="checkbox" required id="active" name="is_active">
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
        // Check if the success message exists in the session
        var successMessage = "{{ session('success') }}";
        if (successMessage) {
            // Display the success message using Alertify.js
            showAlert(successMessage, 'success');
        }

        // Function to display Alertify notification
        function showAlert(message, type) {
            alertify.notify(message, type, 5, function () {
                console.log('dismissed');
            });
        }
        
        // Set the position of the notification to top-right
        alertify.set('notifier', 'position', 'top-right');
    </script>
@stop

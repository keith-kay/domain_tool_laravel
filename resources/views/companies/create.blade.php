@extends('adminlte::page')

@section('title', 'Add Company')

@section('content_header')
    <h1>Add Company</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Add Company</h3>
        <hr class="my-4">
        <form action="{{ route('companies.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control" id="email" name="name" placeholder="Enter Company Name" value="">
                    <span class="text-danger">@error('name') {{$message}} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label">Address</label>
                    <input type="text" required class="form-control" id="email" name="address" placeholder="Enter Company Address" value="">
                    <span class="text-danger">@error('adress') {{$message}} @enderror</span>
                </div>
                <div class="col-md-8">
                    <label for="name" class="form-label">Location</label>
                    <input type="text" required class="form-control" id="email" name="location" placeholder="Enter Company Location" value="">
                    <span class="text-danger">@error('location') {{$message}} @enderror</span>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-database" aria-hidden="true"></i> Submit</button>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-block btn-lg">
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
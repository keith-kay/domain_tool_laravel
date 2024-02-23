@extends('adminlte::page')

@section('title', 'Add Domain')

@section('content_header')
    <h1>Add Domain</h1>
@stop

@section('content')
 <!--preloader-->
 <div class="preloader-container" style="display: none">
    <div class="spinner"></div>
</div>
<style>
    .preloader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8); /* semi-transparent white background */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        border-top: 4px solid #3498db;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .ajs-success {
        background-color: #4CAF50; 
        color: #ffffff;
    }
</style>
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Add Domain</h3>
        <hr class="my-4">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form id="domainForm" action="{{ route('domains.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Domain Name" value="{{ old('name') }}">
                    <span class="text-danger">@error('name') {{ $message }} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label for="company" class="form-label">Company</label>
                    <select required class="form-control" id="company" name="company">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger">@error('company') {{ $message }} @enderror</span>
                </div>               
                <div class="col-md-8">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="text" required class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                    <span class="text-danger">@error('expiry_date') {{ $message }} @enderror</span>
                </div>  
                <div class="col-md-4">
                    <label for="registration_date" class="form-label">Registration Date</label>
                    <input type="text" required class="form-control" id="registration_date" name="registration_date" value="{{ old('registration_date') }}">
                    <span class="text-danger">@error('registration_date') {{ $message }} @enderror</span>
                </div>              
                <div class="col-md-8">
                    <label for="registrar_name" class="form-label">Registrar Name</label>
                    <input type="text" required class="form-control" id="registrar_name" name="registrar_name" placeholder="Enter Registrar Name" value="{{ old('registrar_name') }}">
                    <span class="text-danger">@error('registrar_name') {{ $message }} @enderror</span>
                </div>
                <div class="col-md-4" style="margin-top: 32px;">
                    <button type="button" class="btn btn-primary btn-block" id="lookupButton">
                        <i class="fas fa-search"></i> Lookup
                    </button>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-database" aria-hidden="true"></i> Submit</button>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('domains.index') }}" class="btn btn-secondary btn-block btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to list
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
<script>
 document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired.');

    // Check if the button exists
    var lookupButton = document.getElementById('lookupButton');
    
    

    // Add event listener if the button exists
    if (lookupButton) {
        lookupButton.addEventListener('click', function(event) {
            console.log('Lookup button clicked.');

            event.preventDefault(); // Prevent the default button click behavior

            // Show preloader
            showPreloader();

            console.log('Form Data:', $('#domainForm').serialize());

            // Make an AJAX request to the lookup route
            $.ajax({
                url: "{{ route('lookup') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#domainForm').serialize(),
                success: function(data) {
                    hidePreloader();

                    if (data.status === 'success') {
                        const registrationDate = data.registration_date;
                        const expiryDate = data.expiry_date;
                        const registrarName = data.registrar_name;

                        console.log('Registration Date:', registrationDate);
                        console.log('Expiration Date:', expiryDate);
                        console.log('Registrar Name:', registrarName);

                        // Set the values in the form fields
                        $('#registration_date').val(registrationDate);
                        $('#expiry_date').val(expiryDate);
                        $('#registrar_name').val(registrarName);

                        // Use Alertify.js for success message
                        alertify.success('Domain Found!');
                    } else {
                        console.error('Error:', data.message);
                        // Use Alertify.js for error message
                        alertify.error('Error: ' + data.message);
                    }
                },
                error: function(error) {
                    console.error('Error:', error.responseText);
                    // Use Alertify.js for error message
                    alertify.error('Error: ' + error.responseText);
                },
                complete: function() {
                    // Hide preloader
                    hidePreloader();
                }
            });
        });
    } else {
        console.log('Lookup button not found.');
    }
});

    function validateForm() {
        var companyField = document.getElementById('company');
        if (!companyField.value.trim()) {
            alert('Please fill in the company field.');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    function showPreloader() {
        var preloader = document.querySelector('.preloader-container');
        preloader.style.display = 'flex';
    }

    function hidePreloader() {
        var preloader = document.querySelector('.preloader-container');
        preloader.style.display = 'none';
    }
</script>
@endsection


@extends('adminlte::page')

@section('title', 'Manage Domains')

@section('content_header')
    <h1>Manage Domains</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <form method="post" action="" id="updateDomainsForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h3 class="display-6 mb-0">Manage Domains</h3>
                </div>
                
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-outline-success" id="updateDomainsButton">
                        <i class="fas fa-sync-alt"></i> Update Domains
                    </button>
                </div>
                
            </div>
        </form>
        <hr class="my-4">

        <table class="display" id="domainTable">
            <thead class="border-bottom font-weight-bold">
                <tr>
                    <td> Domain Name </td>
                    <td> Date of Registration</td>
                    <td> Date of Expiry</td>
                    <td> Company <br></td>
                    <td> Registrar Name <br></td>&nbsp;
                   
                    <td> 
                        <a href="{{ route('domains.create') }}" class="btn btn-outline-success">
                            <i class= "fas fa-plus"></i>Add New
                        </a>
                    </td>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td> {{ $domain->name }}</td>
                    <td> {{ $domain->registration_date }}</td>
                    <td> {{ $domain->expiry_date }}</td>
                    <td> {{ $domain->company }}</td>
                    <td> {{ $domain->registrar_name }}</td>
                    @if(auth()->user()->is_admin)
                    <td>
                        <form action="{{ route('domain_delete', $domain->id) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete domain?')">
                                <i class="far fa-trash-alt fa-lg text-danger float-right"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>    
        </table>
    </div>
</div>
</div>
@stop
@section('js')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#domainTable').DataTable({
                lengthChange: false,
                searching: false,
                pageLength: 10,
            });
        
            function showPreloader() {
                var preloader = $('.preloader-container');
                preloader.css('display', 'flex');
            }
        
            function hidePreloader() {
                var preloader = $('.preloader-container');
                preloader.css('display', 'none');
                var spinner = $('.spinner');
                spinner.removeClass('spin');
            }
        
            
            // Attach a submit event listener to the "Update Domains" button
            $('#updateDomainsForm').submit(function (event) {
                event.preventDefault(); // Prevent the default form submission
                
                showPreloader(); // Show the preloader before submitting the form
                
                $.ajax({
                    url: '{% url "update_domains_result" %}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        console.log(data); // Debugging: Log the response data to console
                        hidePreloader(); // Hide the preloader
                        
                        // Display success message
                        showAlert(data.message || successMessage, 'success');
                        // Refresh the page after 2 seconds (adjust the timeout as needed)
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function (xhr, status, error) {
                        hidePreloader(); // Hide the preloader
                        
                        // Display error message
                        showAlert(xhr.responseJSON ? xhr.responseJSON.message || errorMessage : errorMessage, 'error');
                    }
                });
            });
        });
    </script>
@stop
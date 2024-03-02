@extends('adminlte::page')

@section('title', 'Manage Domains')

@section('content_header')
    <h1>Manage Domains</h1>
@stop

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    .colored-toast.swal2-icon-success {
    background-color: #28a745 !important;
    }

    .colored-toast.swal2-icon-error {
    background-color: #f27474 !important;
    }

    .colored-toast.swal2-icon-warning {
    background-color: #f8bb86 !important;
    }

    .colored-toast.swal2-icon-info {
    background-color: #3fc3ee !important;
    }

    .colored-toast.swal2-icon-question {
    background-color: #87adbd !important;
    }

    .colored-toast .swal2-title {
    color: white;
    }

    .colored-toast .swal2-close {
    color: white;
    }

    .colored-toast .swal2-html-container {
    color: white;
    }
</style>
<div class="preloader-container" style="display: none">
    <div class="spinner"></div>
</div>
<div class="container my-3">
@if(session('success'))
    <script>
        const Toast = Swal.mixin({
  toast: true,
  position: 'top-right',
  iconColor: 'white',
  customClass: {
    popup: 'colored-toast',
  },
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
});
(async () => {
  await Toast.fire({
    icon: 'success',
    title: "{{session('success')}}",
  })
})()
</script>
@elseif(session('error'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        (async () => {
            await Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}",
            })
        })();
    </script>
@endif
</div>
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <form id="updateDomainsForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h3 class="display-6 mb-0">Manage Domains</h3>
                </div>
                @if(auth()->user()->is_admin)
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-outline-success" id="updateDomainsButton">
                        <i class="fas fa-sync-alt"></i> Update Domains
                    </button>
                </div>
                @endif
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
                    <td> Registrar Name <br></td>
                    @if(auth()->user()->is_admin)
                    <th>
                        <a href="{{ route('domains.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td> {{ $domain->name }}</td>
                    <td> {{ $domain->registration_date }}</td>
                    <td> {{ $domain->expiry_date }}</td>
                    <td> {{ $domain->company->name }}</td>
                    <td> {{ $domain->registrar_name }}</td>
                    @if(auth()->user()->is_admin)
                    <td>
                        <form action="{{ route('domains.destroy', $domain->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
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
        
            // Attach click event listener to the "Update Domains" button
            $('#updateDomainsButton').click(function () {
                showPreloader(); // Show preloader
                
            $.ajax({
            url: '{{ route("domains.updateExpiryDates") }}',
            method: 'POST',
            data: $('#updateDomainsForm').serialize(),
            success: function (data) {
                console.log(data); // Log response data
                hidePreloader(); // Hide preloader
                
                // Define success message
                var successMessage = data.message;

                // Display success message using SweetAlert2
                Swal.fire({
                    toast: true,
                    position: 'top-right',
                    icon: 'success',
                    iconColor: 'white',
                    title: successMessage,
                    customClass: {
                        popup: 'colored-toast',
                    },
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                

                // Reload the page after successful update
                setTimeout(function() {
                    // Reload the page after successful update
                    window.location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log error response
                hidePreloader(); // Hide preloader
                // Handle error
            }
        });
            });
        });
    </script>
@stop

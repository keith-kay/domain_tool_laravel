
@extends('adminlte::page')

@section('title', 'Manage Users')

@section('content_header')
    <h1>Manage Users</h1>
@stop

@section('content')
@if(auth()->user()->is_admin)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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
        <form method="post" action="" id="updateDomainsForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h3 class="display-6 mb-0">Manage Users</h3>
                </div>
            </div>
        </form>
        <hr class="my-4">

        <table class="display" id="userTable">
            <thead class="border-bottom font-weight-bold">
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    @if(auth()->user()->is_admin)
                        <td>
                            <a href="{{ route('users.create') }}" class="btn btn-outline-success">
                                <i class="fas fa-plus"></i> Add New User
                            </a>
                        </td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    @if(auth()->user()->is_admin)
                    <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary  mx-2">
                        <i class="far fa-edit fa-lg"></i> 
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the user?')">
                                <i class="far fa-trash-alt"></i> 
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
@else
        <div class="alert alert-danger">
            You do not have permission to access this page. Please contact the administrator for access rights.
        </div>
        <div class="alert text-center my-5">
        <i class="fa fa-ban" aria-hidden="true" style="color: red; font-size: 80px;"></i>
        </div>
@endif
@stop

@section('js')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#userTable').DataTable({
                lengthChange: false,
                pageLength: 10,
                initComplete: function () {
                    // Delay the execution by 100 milliseconds
                    setTimeout(function () {
                        fetchAndUpdateUpdatedColumn(table);
                    }, 100);
                    this.api().columns().every(function (index) {
                       
                    });
                }
            });
        })

        alertify.set('notifier', 'position', 'top-right');
        // Function to display Alertify notification
        function showAlert(message, type) {
            alertify.notify(message, type, 5);
        }

        // Check if there's an error or success message in the messages framework
                
                // Display Alertify notification based on the message type
                if (messagesExist) {
                    showAlert(errorMessage || successMessage, errorMessage ? 'error' : 'success');
                }
    </script>
@stop

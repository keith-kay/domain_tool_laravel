@extends('adminlte::page')

@section('title', 'Manage Users')

@section('content_header')
    <h1>Manage Users</h1>
@stop

@section('content')
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
                    <td>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('users.create') }}" class="btn btn-outline-success">
                                <i class= "fas fa-plus"></i> Add New User
                            </a>
                        @endif
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
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

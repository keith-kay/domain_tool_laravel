@extends('adminlte::page')

@section('title', 'Manage Companies')

@section('content_header')
    <h1>Company</h1>
@stop

@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <!-- Alertify -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <div class="jumbotron">
    <h3 class="display-6">Manage Companies</h3>
     <hr class="my-4">
        <table class="table" id="companyTable">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Company Address</th>
                    <th>Company Location</th>
                    
                    <th>
                        <a href="{{ route('companies.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->address }}</td>
                    <td>{{ $company->location }}</td>
                    <td>
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-secondary mx-2">
                            <i class="far fa-edit"></i> 
                        </a>
                        <form action="{{ route('companies.destroy', $company->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete company?')">
                                <i class="far fa-trash-alt"></i> 
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#companyTable').DataTable();
        });

        alertify.set('notifier', 'position', 'top-right');

        // Function to display Alertify notification
        function showAlert(message, type) {
            alertify.notify(message, type, 5);
        }

        // Check if there's an error or success message in the messages framework
        var messagesExist = {!! json_encode($errors->any() || session()->has('success')) !!};

        var errorMessage = "{!! $errors->first() !!}";

        var successMessage = "{!! session()->get('success') !!}";

        // Display Alertify notification based on the message type
        if (messagesExist) {
            showAlert(errorMessage || successMessage, errorMessage ? 'error' : 'success');
        }
    </script>
@stop

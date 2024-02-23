@extends('adminlte::page')

@section('title', 'Delete Company')

@section('content_header')
    <h1>Company</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Delete Company</h3>
        <hr class="my-4">
        <p>Are you sure you want to delete this company?</p>
        <form action="{{ route('companies.destroy', $company->id) }}" method="post">
            @csrf
            @method('DELETE')
            <div class="row my-3">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-danger btn-block btn-lg"><i class="far fa-trash-alt"></i> Delete</button>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-block btn-lg">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
    <script>
        $(document).ready(function(){
            showAlert("Company deleted successfully", "success");
        });

        function showAlert(message, type) {
            alertify.set('notifier', 'position', 'top-right');
            alertify.notify(message, type, 5);
        }
    </script>
@stop
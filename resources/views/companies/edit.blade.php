@extends('adminlte::page')

@section('title', 'Edit Company')

@section('content_header')
    <h1>Company</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <h3 class="display-6">Edit Company</h3>
        <hr class="my-4">
        <form action="{{ route('companies.update', $company->id) }}" method="post" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Company Name" value="{{ $company->name }}">
                    <span class="text-danger">@error('name') {{ $message }} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" required class="form-control" id="address" name="address" placeholder="Enter Company Address" value="{{ $company->address }}">
                </div>
                <div class="col-md-8">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" required class="form-control" id="location" name="location" placeholder="Enter Company Location" value="{{ $company->location }}">
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fas fa-save"></i> Update</button>
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

@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('create role') }}
@endsection
@csrf
@section('content')
    {{--Update Status--}}
    @include('dashboard.status.status')
    {{--simple error tracing--}}
    @include('dashboard.simple error tracing.simple_error_tracing')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst($page_title) }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('dashboard.roles.store') }}" class="form-group mb-0"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <!-- _token input -->
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <!-- Role name input -->
                <div class="form-group">
                    <label for="inputRoleName">Role name</label>
                    <input name="role_name" type="text" class="form-control" id="inputRoleName"
                           placeholder="Enter role name">
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-transparent pl-0 pr-0">
                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Create new Role" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </div>
@endsection

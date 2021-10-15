@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('edit role') }}
@endsection
@section('content')
    @csrf
    {{--Update Status--}}
    @include('dashboard.status.status')
    {{--simple error tracing--}}
    @include('dashboard.simple error tracing.simple_error_tracing')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst(trans($page_title.' '.$role->id)) }}
                <small>Created at{{ date_format($role->created_at, 'jS M Y') }} / Updated
                    at{{ date_format($role->updated_at, 'jS M Y') }}</small>
            </h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('dashboard.roles.update',$role->id) }}" class="form-group mb-0"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- _token input -->
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <!-- Role name input -->
                <div class="form-group">
                    <label for="inputRoleName">Role name</label>
                    <input name="role_name" type="text" class="form-control" id="inputRoleName"
                           placeholder="Enter role name" value="{{ $role->name }}">
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-transparent pl-0 pr-0">
                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Save Changes" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </div>
@endsection

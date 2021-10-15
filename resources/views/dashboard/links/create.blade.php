@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('Generate Shorten Link') }}
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
        <form method="POST" action="{{ route('dashboard.links.store') }}" class="form-group mb-0"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <!-- _token input -->
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <!-- Link input -->
                <div class="form-group">
                    <label for="inputLink">Link</label>
                    <input name="link" type="text" class="form-control @error('link') is-invalid @enderror"
                           id="inputLink"
                           placeholder="Enter Url" value="{{ old('link') }}">
                    @error('link')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('dashboard.links.index') }}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Generate Shorten Link" class="btn btn-success float-right">
            </div>
        </form>
    </div>
@endsection

@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('create users') }}
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
        <form method="POST" action="{{ route('dashboard.users.store') }}" class="form-group mb-0"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <!-- _token input -->
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <!-- Username input -->
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           id="inputUsername"
                           placeholder="Enter username" value="{{ old('name') }}">
                    @error('name')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- User Role input -->
                <div class="form-group">
                    <label>Select Roles :</label><br>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-6">
                                <label class="custom-control "><input type="checkbox" name="roles[]"
                                                                      class="form-check-input"
                                                                      value="{{ $role->id }}"
                                                                      {{--{{( is_array(old('roles')) && in_array($role->id, old('roles'), true)  ? 'checked' : '' )}}--}}
                                                                      @if(is_array(old('roles')) && in_array($role->id, old('roles'), false)) checked @endif

                                    >{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Email input -->
                <div class="form-group">
                    <label for="InputEmail">Email address</label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           id="InputEmail" placeholder="Enter email" value="{{ old('email') }}">
                    @error('email')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Password input -->
                <div class="form-group">
                    <label for="InputPassword">Password</label>
                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           id="InputPassword"
                           placeholder="Password">
                    @error('password')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Confirm Password input -->
                <div class="form-group">
                    <label for="InputConfirmPassword">Confirm Password</label>
                    <input name="password_confirmation" type="password"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           id="InputConfirmPassword"
                           placeholder="Retype password">
                    @error('password_confirmation')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Create new User" class="btn btn-success float-right">
            </div>
        </form>
    </div>
@endsection

@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('edit user') }}
@endsection
@csrf
@section('content')
    {{--Update Status--}}
    @include('dashboard.status.status')
    {{--simple error tracing--}}
    @include('dashboard.simple error tracing.simple_error_tracing')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst(trans($page_title.' '.$user->id)) }}
                <small>Created at{{ date_format($user->created_at, 'jS M Y') }} / Updated
                    at{{ date_format($user->updated_at, 'jS M Y') }}</small>
            </h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('dashboard.users.update',$user->id) }}" class="form-group mb-0"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- _token input -->
                <div class="form-group">
                    {{ csrf_field() }}
                </div>
                <!-- Username input -->
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input name="username" type="text" class="form-control @error('username') is-invalid @enderror"
                           id="inputUsername"
                           placeholder="Enter username" value="{{ $user->name }}">
                    @error('username')
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
                                                                      @foreach($user_roles as $user_role)
                                                                      @if( $user_role->role_id  === $role->id) checked @endif @endforeach
                                    >{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Email input -->
                <div class="form-group">
                    <label for="InputEmail">Email address</label>
                    <input name="email" type="email @error('email') is-invalid @enderror" class="form-control"
                           id="InputEmail" placeholder="Enter email"
                           value="{{ $user->email }}">
                    @error('email')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Password input -->
                <div class="form-group">
                    <label for="InputPassword">Password</label>
                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           id="InputPassword"
                           placeholder="Password" value="{{ $user->password }}">
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
                           placeholder="Retype password" value="{{ $user->password }}">
                    @error('password_confirmation')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class=" card-footer">
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Save Changes" class="btn btn-success float-right">
            </div>
        </form>
    </div>
@endsection

@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('edit roles for users') }}
@endsection
@section('content')
    @csrf
    {{--Update Status--}}
    @include('dashboard.status.status')
    {{--simple error tracing--}}
    @include('dashboard.simple error tracing.simple_error_tracing')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst(trans(substr($page_title,0,-1).' '.$user->id)) }}
{{--                <small>Created at{{ date_format($user_roles[0]->created_at, 'jS M Y') }} / Updated--}}
{{--                    at{{ date_format($user_roles[0]->updated_at, 'jS M Y') }}</small>--}}
                <small>Created at {{ $user_roles[0]->created_at }} / Updated
                    at {{ $user_roles[0]->updated_at }}</small>
            </h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('dashboard.users.roles.update',$user->id) }}" class="form-group mb-0"
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
                    <select name="username" id="inputUsername"
                            class="form-control custom-select @error('username') is-invalid @enderror">
                        <option selected="selected" disabled>Select one</option>
                        <option value="{{ $user->id }}"
                                @if( $user_roles[0]->user_id  === $user->id) selected="selected" @endif >{{ $user->name }}</option>
                    </select>
                    @error('username')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Select Roles :</label><br>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-6">
                                <label class="custom-control"><input type="checkbox" name="roles[]"
                                                                     class="form-check-input"
                                                                     value="{{ $role->id }}"
                                                                     @foreach($user_roles as $user_role)
                                                                     @if( $user_role->role_id  === $role->id) checked @endif @endforeach>
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('dashboard.users.roles.index') }}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Save Changes" class="btn btn-success float-right">
            </div>
        </form>
    </div>
@endsection

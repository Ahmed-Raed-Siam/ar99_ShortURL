@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('create roles for user') }}
@endsection
@section('content')
    @csrf
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
        <form method="POST" action="{{ route('dashboard.users.roles.store') }}" class="form-group mb-0"
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
                    <select name="username" id="inputUsername"
                            class="form-control custom-select @error('username') is-invalid @enderror">
                        <option selected="selected" disabled>Select one</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    @if( (int)old('username')  === $user->id) selected="selected" @endif >{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('username')
                    <span class="text-sm text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{--Select Role For User Checkbox input--}}
                <div class="form-group">
                    <label>Select Roles :</label><br>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-6">
                                <label class="custom-control "><input type="checkbox" name="roles[]"
                                                                      class="form-check-input"
                                                                      value="{{ $role->id }}"
                                                                      @if( is_array(old('roles')) && in_array($role->id, old('roles'), false)) checked @endif
                                        {{--@if( old('roles')  === $role->id) checked @endif--}}
                                    >{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    {{--                    <div class="custom-control custom-checkbox">
                                            @foreach($roles as $role)
                                                <label class="custom-control "><input type="checkbox" name="roles[]"
                                                                                      class="form-check-input"
                                                                                      value="{{ $role->id }}">{{ $role->name }}</label>
                                            @endforeach
                                        </div>--}}

                    {{--                    <div class="custom-control custom-checkbox">
                                            @foreach($roles as $role)
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                       class="custom-control-input custom-control-input-blue"
                                                       id="customCheckbox">
                                                <label for="customCheckbox" class="custom-control-label">{{ $role->name }}</label>
                                            @endforeach
                                        </div>--}}
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('dashboard.users.roles.index') }}" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Create new Role for user" class="btn btn-success float-right">
            </div>
        </form>
    </div>
@endsection

@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('view user in the trash') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst(trans(substr($page_title,0,strpos($page_title,'user')).' '.$user->id .''.substr($page_title,strpos($page_title,'user'),strlen($page_title))))
    }}</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <dl class="card-body row">
                <dt class="col-sm-3">User ID</dt>
                <dd class="col-sm-9">{{ $user->id }}</dd>

                <dt class="col-sm-3">User name</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>

                <dt class="col-sm-3">User email</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">User password</dt>
                <dd class="col-sm-9">{{ $user->password }}</dd>

                <dt class="col-sm-3">Created at</dt>
                <dd class="col-sm-9">{{ date('F d, Y', strtotime($user->created_at)) }}</dd>

                <dt class="col-sm-3">Updated at</dt>
                <dd class="col-sm-9">{{ date('F d, Y', strtotime($user->updated_at)) }}</dd>
            </dl>
        </div>
    </div>
@endsection

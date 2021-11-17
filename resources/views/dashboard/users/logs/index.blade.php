@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('user logs') }}
@endsection
@section('content')
    {{--Update Status--}}
    @include('dashboard.status.status')
    <div class="card p-2">
        <div class="card-header">
            <h3 class="card-title">{{ ucfirst($page_title) }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body p-0">
            <table class="table table-hover table-responsive table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 20%">Short Link</th>
                    <th style="width: 10%">hits</th>
                    <th style="width: 12%">ip</th>
                    <th style="width: 30%">user_agent</th>
                    <th style="width: 10%">visited_at</th>
                    <th style="width: 20%">
                        <form class="form btn btn-outline-primary m-auto d-flex text-center float-right"
                              action="{{ route('dashboard.user.logs.destroy', ['user_id' => $userLogs->first() ? $userLogs->first()->user_id : \Illuminate\Support\Facades\Auth::id()]) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            {{ csrf_field() }}
                            <button class="custom-btn btn btn-outline-primary m-0 p-0 border-0" type="submit"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Clear User {{ $userLogs->first() ? $userLogs->first()->user_id : \Illuminate\Support\Facades\Auth::id() }} logs">
                                <i class="fas fa-broom p-1"></i>
                                Clear Logs
                            </button>
                        </form>
                        {{--                        <a class="btn btn-outline-primary m-auto d-flex text-center float-right"--}}
                        {{--                           href="{{ route('dashboard.user.logs.destroy',['user_id' => $userLogs->first()->user_id]) }}"--}}
                        {{--                           data-toggle="tooltip" data-placement="top"--}}
                        {{--                           title="Clear User {{ $userLogs->first()->user_id }} logs">--}}
                        {{--                            <i class="fas fa-eraser p-1"></i>--}}
                        {{--                            <i class="fas fa-magic p-1"></i>--}}
                        {{--                            <i class="fas fa-broom p-1"></i>--}}
                        {{--                            Clear Logs--}}
                        {{--                        </a>--}}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse ($userLogs as $log)
                    <tr>
                        <td>
                            #{{ $counter++ }}
                        </td>
                        <td>
                            <a>
                                {{ 'ar99/'.$log->short_link }}
                            </a>
                            <br/>
                            <small>
                                Created {{ $log->created_at }}
                            </small>
                        </td>
                        <td>
                            <a>
                                {{ $log->hits }}
                            </a>
                        </td>
                        <td>
                            <a>
                                {{ $log->ip }}
                            </a>
                        </td>

                        <td>
                            <a>
                                {{ $log->user_agent }}
                            </a>
                        <td colspan="2">
                            <a>
                                {{--{{  date('F d, Y', strtotime($log->visited_at)) }}--}}
                                {{  date('F d ,Y H:i:s a', strtotime($log->visited_at)) }}
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

        <div class="card-footer w-100 m-0 pt-sm-2 pr-sm-2 pl-sm-1 bg-light">
            <div class="d-block p-2">
                <ul class="pagination m-auto d-flex justify-content-center float-right ">
                    {!! $userLogs->links('vendor.pagination.custom') !!}
                </ul>
            </div>
            <!-- /.card-footer -->

        </div>
@endsection

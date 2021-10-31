@extends('dashboard.layout.master')

@section('page-title')
    {{ $page_title=ucwords('links table') }}

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
            <table class="table table-hover table-responsive table-striped projects data-table">
                <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th style="width: 30%">Link</th>
                    <th style="width: 12%">Code</th>
                    <th style="width: 12%">Short Link</th>
                    <th style="width: 10%">Hits</th>
                    <th style="width: 5%">Created</th>
                    <th style="width: 20%">
                        <a class="btn btn-outline-primary m-auto d-flex text-center float-right"
                           href="{{ route('dashboard.links.create') }}"
                           data-toggle="tooltip" data-placement="top"
                           title="ADD Link {{ $counter }}">
                            <i class="fas fa-plus-square p-1"></i>
                            Make Short Link
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse ($links as $link)
                    <tr>
                        <td>
                            #{{ $counter++ }}
                        </td>
                        <td>
                            <a>
                                {{ $link->link }}
                            </a>
                            <br/>
                            <small>
                                Created {{ $link->created_at }}
                            </small>
                        </td>
                        <td>
                            <a>
                                {{ $link->code }}
                            </a>
                        </td>
                        <td>
                            <a class="short-link" href="{{ route('dashboard.shorten.link',$link->code) }}"
                               target="_blank">
                                {{--  {{ route('shorten.link', $link->code) }}--}}
                                {{ 'ar99/'.$link->short_link }}
                            </a>
                        </td>
                        <td>
                            <a id="total_hits" class="fa fa-clock text-cyan">
                                {{ $link->total_hits }}
                            </a>
                        </td>
                        <td>
                            <a>
                                {{ date('F d, Y', strtotime($link->created_at)) }}
                            </a>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" style="padding-top: 6px;padding-bottom: 6px;"
                               href="{{ route('dashboard.links.show',['link' => $link->id]) }}"
                               data-toggle="tooltip" data-placement="top"
                               title="View link {{ $counter-1 }}">
                                <i class="fas fa-external-link-alt"></i>
                            </a>

                            {{-- OR This with Label Text --}}
                            {{--<a class="btn btn-primary btn-sm"--}}
                            {{--   --}}{{--href="{{ route('dashboard.links.show',$link->id) }}"--}}
                            {{--   --}}{{--OR--}}
                            {{--   href="{{ route('dashboard.links.show',['link' => $link->id]) }}"--}}
                            {{--   data-toggle="tooltip" data-placement="top"--}}
                            {{--   title="View link {{ $counter-1 }}">--}}
                            {{--    <i class="fas fa-external-link-alt"></i>--}}
                            {{--    View--}}
                            {{--</a>--}}

                            <a class="btn btn-info btn-sm" style="padding-top: 6px;padding-bottom: 6px;"
                               href="{{ route('dashboard.links.edit',['link' => $link->id]) }}"
                               data-toggle="tooltip" data-placement="top"
                               title="Edit link {{ $counter-1 }}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            {{-- OR This with Label Text --}}
                            {{--<a class="btn btn-info btn-sm"--}}
                            {{--   href="{{ route('dashboard.links.edit',['link' => $link->id]) }}"--}}
                            {{--   data-toggle="tooltip" data-placement="top"--}}
                            {{--   title="Edit link {{ $counter-1 }}">--}}
                            {{--    <i class="fas fa-pencil-alt"></i>--}}
                            {{--    Edit--}}
                            {{--</a>--}}
                            <form class="btn btn-danger btn-sm m-0 "
                                  action="{{ route('dashboard.links.destroy', ['link' => $link->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                {{ csrf_field() }}
                                <button class="btn btn-sm text-white m-0 p-0" type="submit" data-toggle="tooltip"
                                        data-placement="top"
                                        title="Delete link {{ $counter-1 }}"><i class="fas fa-trash-alt"></i></button>
                                {{-- OR This with Label Text --}}
                                {{--<i class="fas fa-trash-alt"></i>--}}
                                {{--<input name="delete" type="submit" class="btn btn-danger btn-sm p-0"--}}
                                {{--       value="Delete"--}}
                                {{--       data-toggle="tooltip" data-placement="top"--}}
                                {{--       title="Delete link {{ $counter-1 }}">--}}
                            </form>
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
                    {!! $links->links('vendor.pagination.custom') !!}
                </ul>
            </div>
            <!-- /.card-footer -->

        </div>
    </div>
@endsection

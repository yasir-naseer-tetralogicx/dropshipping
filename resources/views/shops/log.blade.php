@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Logs
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.logs') }}">Logs</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <select name="role_search" id="" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                @if($user->id == '1')
                                    selected
                                @endif
                            >
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="type_search" id="" class="form-control">
                        <option value="product">Product</option>
                        <option value="order">Order</option>
                        <option value="log">Log in</option>
                    </select>
{{--                    <input type="search" class="form-control" placeholder="Search Logs By Type" value="{{$search}}" name="search" required >--}}
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="/admin/users/logs"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>


        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Logs</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($logs)>0)
                    <ul class="list-group px-1 list-unstyled">

                        @foreach($logs as $log)
{{--                            @dd($log)--}}
                            <li class="pb-3">
                                    <span class="badge badge-success">@if(isset($log->user->name)){{ $log->user->name }}@endif</span>
                                    <strong class="badge badge-primary">@if(isset($log->user->role)){{ $log->user->role }}@endif</strong>
                                    <strong>{{ $log->type }} </strong><span class="badge badge-success text-white">@if(isset($log->item)){{ $log->item }}@endif</span>
                                    ( {{ $log->date }} )
{{--                                    {{ $log->location }}--}}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class=" pl-0">No data!</p>
                @endif
                <div class="d-flex justify-content-end">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->
@endsection

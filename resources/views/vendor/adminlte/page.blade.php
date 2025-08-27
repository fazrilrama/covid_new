@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img src={{ asset('logo-alt.png') }}></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src={{ asset('logo.png') }}></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        @if(Auth::user()->hasRole(['WarehouseSupervisor', 'WarehouseOfficer']))
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Gudang<span class="caret">
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a>   
                                            {{ session()->get('warehouse_name')}}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Branch<span class="caret">
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    @if(Auth::user()->branch) 
                                        <li>
                                            <a>
                                                {{Auth::user()->branch->name}}
                                            </a>
                                        </li> 
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <li role="presentation" class="Form">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="">
                                    INFO <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a style="padding:0px 10px">
                                        IP Address : {{ Request::ip() }}
                                    </a>
                                </li>
                                <li>
                                    <a style="padding:0px 10px">
                                        {{ \Carbon\Carbon::now()->format('l, j F Y h:i A') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if(Auth::user()->hasRole('WarehouseSupervisor'))
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        {{-- Notifications <span class="badge" style="font-size:9px !important;">{{ Auth::user()->whsNotification()->count() }}</span> --}}
                                    </span>
                                </a>
                                {{-- @if(Auth::user()->whsNotification()->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach(Auth::user()->whsNotification() as $notification)
                                            <li>
                                                <a href="{{ url('advance_notices/' . $notification->id. '/show') }}">Anda memiliki {{ $notification->type == 'inbound' ? 'AIN' : 'AON' }} ({{ $notification->code }}) pada projek ({{ $notification->project->name }}) baru.</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </li>
                        @endif
                        @if(Auth::user()->hasRole('WarehouseManager'))
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        {{-- Notifications <span class="badge" style="font-size:9px !important;">{{ Auth::user()->whmNotification()->count() }}</span> --}}
                                    </span>
                                </a>
                                {{-- @if(Auth::user()->whmNotification()->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach(Auth::user()->whmNotification() as $notification)
                                            <li>
                                                <a href="{{ url('advance_notices/' . $notification->id. '/show') }}">Anda memiliki {{ $notification->type == 'inbound' ? 'AIN' : 'AON' }} ({{ $notification->code }}) pada projek ({{ $notification->project->name }}) yang belum di assign.</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </li>
                        @endif
                        @if(Auth::user()->hasRole('Transporter'))
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        {{-- Notifications <span class="badge" style="font-size:9px !important;">{{ Auth::user()->completeNotification()->count() }}</span> --}}
                                    </span>
                                </a>
                                {{-- @if(Auth::user()->completeNotification()->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach(Auth::user()->completeTransporterNotification() as $notification)
                                            <li>
                                                <a href="{{ url('stock_entries/' . $notification->id. '/show') }}">Anda memiliki ({{ $notification->code }}) pada projek ({{ $notification->project->name }}) yang belum di baca.</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </li>
                        @endif
                        @if(Auth::user()->hasRole('CargoOwner'))
                            <li role="presentation" class="Form">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="">
                                        Notifications <span class="badge" style="font-size:9px !important;">0</span>
                                        {{-- Notifications <span class="badge" style="font-size:9px !important;">{{ Auth::user()->whcNotification()->count() }}</span> --}}
                                    </span>
                                </a>
                                {{-- @if(Auth::user()->whcNotification()->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach(Auth::user()->whcNotification() as $notification)
                                            <li>
                                                <a href="{{ url('advance_notices/' . $notification->id. '/edit') }}">Anda memiliki {{ $notification->type == 'inbound' ? 'AIN' : 'AON' }} ({{ $notification->code }}) pada projek ({{ $notification->project->name }}) yang ditolak oleh manager.</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </li>
                        @endif                      
                        @if(!Auth::user()->hasRole('Superadmin'))                  
                            @if(session()->get('projects')->count()>1)
                                <li class="select-project-class">
                                    <select class="select2" id="select-project">
                                        @foreach(session()->get('projects') as $project)
                                            @if($project->id == session()->get('current_project')->id)
                                                <option selected value="{{ url('profile/project/'.$project->id) }}">
                                                    {{$project->name}}
                                                </option>
                                            @else
                                                <option value="{{url('profile/project/'.$project->id)}}">
                                                    {{$project->name}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </li>
                            @else
                                <li>
                                    <li>
                                        <p class="navbar-text">
                                            <i class="fa fa-list-alt"></i> {{ empty(session()->get('current_project')) ?'': session()->get('current_project')->name}}
                                        </p>
                                    </li>
                                </li>
                            @endif
                            </li>
                        @endif
                        <li role="presentation" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="">
                                    <i class="fa fa-user-circle"></i>
                                    {{Auth::User()->user_id}}
                                    <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{route('profile')}}" title="View Profile">Profile</a>
                                </li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ trans('adminlte::adminlte.log_out') }}
                                    </a>
                                    <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                        @if(config('adminlte.logout_method'))
                                            {{ method_field(config('adminlte.logout_method')) }}
                                        @endif
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @include('includes.notification')

                @yield('content')
            </section>
            
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
              <b>Version</b> 1.0.0
            </div>
            <strong>© 2020 Powered By <a href="https://www.bgrlogistics.id/">BGR ACCESS</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
    <script>
        // the selector will match all input controls of type :checkbox
        // and attach a click event handler 
        $("input:checkbox.radiouser").on('click', function() {
          // in the handler, 'this' refers to the box clicked on
          var $box = $(this);
          if ($box.is(":checked")) {
            // the name of the box is retrieved using the .attr() method
            // as it is assumed and expected to be immutable
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            // the checked state of the group/box on the other hand will change
            // and the current value is retrieved using .prop() method
            $(group).prop("checked", false);
            $box.prop("checked", true);
          } else {
            $box.prop("checked", false);
          }
        });
    </script>
@stop


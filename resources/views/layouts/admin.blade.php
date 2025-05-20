<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Game Shop Admin Panel</title>

    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('bower_components/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ url('/admin') }}" class="brand-link">
            <img src="/img/AdminLTELogo.png" alt="Game Shop Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Game Shop Admin</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Admin
                        @endauth
                    </a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ url('/admin') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/creators') }}" class="nav-link">
                            <i class="nav-icon fas fa-gamepad"></i>
                            <p>Creators</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/games') }}" class="nav-link">
                            <i class="nav-icon fas fa-gamepad"></i>
                            <p>Games</p>
                        </a>
                    </li>

                    @role('admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/users') }}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    @endrole



                    @role('admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/support') }}" class="nav-link">
                            <i class="nav-icon fas fa-life-ring"></i> {{-- life-ring is good for support/help --}}
                            <p>Support Requests</p>
                        </a>
                    </li>
                    @endrole

                    @role('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.fetch-games') }}">
                            <i class="fas fa-download"></i> Fetch Games from RAWG API
                        </a>
                    </li>
                    @endrole

                    @role('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.update.prices') }}">
                            <i class="fas fa-download"></i> Update Prices
                        </a>
                    </li>
                    @endrole


                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
       </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright © <script>document.write(new Date().getFullYear())</script> Game Shop.</strong> All rights reserved.
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    </div>
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bower_components/fontawesome/js/all.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script>

</body>
</html>

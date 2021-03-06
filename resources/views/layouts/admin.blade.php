<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('meta-title', __('Dashboard'))</title>

    {{-- app --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/adminlte.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/summernote/summernote-bs4.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <form action="{{ route('logout') }}" class="d-inline-block" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <span class="brand-text font-weight-light">Owsan Dashboard</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('Dashboard') }}</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ request()->routeIs('admin.product-category.*') ? 'menu-open' : '' }}">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.product-category.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Category
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.product-category.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.product-category.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Categories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.product-category.create') }}"
                                   class="nav-link {{ request()->routeIs('admin.product-category.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                        </ul>


                    </li>

                    <li class="nav-item has-treeview {{ request()->routeIs('admin.brand.*') ? 'menu-open' : '' }}">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Brand
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.brand.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Brand</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.create') }}"
                                   class="nav-link {{ request()->routeIs('admin.brand.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview {{ request()->routeIs('admin.product.*') ? 'menu-open' : '' }}">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Product
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.product.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.product.create') }}"
                                   class="nav-link {{ request()->routeIs('admin.product.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                        </ul>
                    </li>


                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content pt-3">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Powered By Laravel
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2014-{{ date('Y') }} Owsan</strong> All right reserved
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/backend/js/adminlte.min.js') }}"></script>
<!-- summernote -->
<script src="{{ asset('assets/backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('assets/backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
    $(document).ready(function () {
        // text-editor
        $('.text-editor').summernote({
            height: 300,
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview']],
            ],
            styleTags: [
                'p', {
                    title: 'Blockquote',
                    tag: 'blockquote',
                    className: 'blockquote',
                    value: 'blockquote'
                }, 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
        });

        // file-upload
        bsCustomFileInput.init();

        // sweet-alert-2
        @if(session()->has('success'))
        toaster("success", "{{ session()->get('success') }}")
        @endif

        @if(session()->has('warning'))
        toaster("warning", "{{ session()->get('warning') }}")
        @endif

        @if(session()->has('error'))
        toaster("error", "{{ session()->get('error') }}")
        @endif
    });
</script>
@yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Insentif Supir </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{url('css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{url('css/ionicons.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{url('css/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{url('css/OverlayScrollbars.min.css')}}">

    <link rel="stylesheet" href="{{url('css/dataTables.bootstrap4.min.css')}}">

    <link rel="stylesheet" href="{{url('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('css/select2-bootstrap4.min.css')}}">

    <link rel="stylesheet" href="{{url('css/toastr.min.css')}}">

    <link rel="stylesheet" href="{{url('css/daterangepicker.css')}}">


    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                {{--  <li class="nav-item d-none d-sm-inline-block">
                    <a href="../../index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>  --}}
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
                        Keluar
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../../index3.html" class="brand-link text-center">
                {{--  <img src="{{url('img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">  --}}
                <span class="brand-text font-weight-light">TB Baja</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{url('img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{Auth::user()->name}}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                        @foreach(Config::get("global_vars.pages_" . Auth::user()->role) as $menu)
                            @if(!isset($menu["nested_menu"]))
                                <li class="nav-item">
                                    <a href="{{url('/')}}{{$menu['url']}}" class="nav-link @if($menu['url'] == "/" . request()->path()) active @endif">
                                        <i class="nav-icon fas fa-{{$menu['icon']}}"></i>
                                        <p>{{$menu['name']}}</p>
                                    </a>
                                </li>
                            @else
                                <li
                                    @if(in_array("/" . request()->path(), $menu['urls']))
                                        class="nav-item has-treeview menu-open"
                                    @else
                                        class="nav-item has-treeview"
                                    @endif>
                                    <a href="#" class="nav-link @if(in_array("/" . request()->path(), $menu['urls'])) active @endif">
                                        <i class="nav-icon fas fa-{{$menu['icon']}}"></i>
                                        <p>
                                            {{$menu['name']}}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach($menu['nested_menu'] as $nested_menu)
                                            <li class="nav-item">
                                                <a href="{{url('/')}}{{$nested_menu['url']}}" class="nav-link @if($nested_menu['url'] == "/" . request()->path()) active @endif">
                                                    <i class="nav-icon fas fa-{{$nested_menu['icon']}}"></i>
                                                    <p>{!!$nested_menu['name']!!}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>
                                @yield('title')
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                {{--  <li class="breadcrumb-item"><a href="#">Layout</a></li>  --}}
                                @foreach($breadcrumbs as $b)
                                    <li class="breadcrumb-item"><a href="{{$b['link']}}">{{$b['nama']}}</a></li>
                                @endforeach
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    @yield('main-content-section')
                    {{--  <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Title</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus"></i></button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove"
                                            data-toggle="tooltip" title="Remove">
                                            <i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    Start creating your amazing application!
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    Footer
                                </div>
                                <!-- /.card-footer-->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>  --}}
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            {{--  <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.0-rc.1
            </div>  --}}
            <strong>Copyright &copy; 2019 <a href="{{url('/')}}">Aplikasi Insentif Supir TB Baja</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- ./wrapper -->

    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus data <span id="master"></span> dengan ID=<span id="id_hapus"></span> {{"?"}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-hapus" master="mobil">Ya</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- jQuery -->
    <script src="{{url('js/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{url('js/bootstrap.bundle.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{url('js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{url('js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    {{--  <script src="../../dist/js/demo.js"></script>  --}}

    <script src="{{url('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{url('js/select2.full.min.js')}}"></script>

    <script src="{{url('js/toastr.min.js')}}"></script>

    <script src="{{url('js/moment.min.js')}}"></script>
    <script src="{{url('js/daterangepicker.js')}}"></script>


    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var configDataTable = {
                language: {
                    "info"          : "Menampilkan data nomor _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty":      "",
                    "lengthMenu"    : "Menampilkan _MENU_ data",
                    "search"        : "Cari:",
                    "emptyTable"    : "Data tidak ditemukan",
                    "zeroRecords"   : "Data tidak ditemukan",
                    "paginate": {
                        "first"     : "Pertama",
                        "last"      : "Terakhir",
                        "next"      : "Selanjutnya",
                        "previous"  : "Sebelumnya"
                    },
                    "infoFiltered": " - disaring dari _MAX_ data"
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            };

            $('.datatable').DataTable(configDataTable);

            configDataTable.ordering = false;
            $('.datatable-no-order').DataTable(configDataTable);

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            var htmlButton = '';

            function setLoadingButton(button){
                //tempButton = button;
                htmlButton = button.html();
                button.attr('disabled', 'disabled');
                button.html('<i class="fas fa-sync fa-spin"></i> Memuat');
            }

            function restoreButton(button){
                //console.log(htmlButton);
                button.removeAttr('disabled');
                button.html(htmlButton);
            }

            function clearErrorMessages(){
                $('.invalid-feedback').html('');
                $('.form-control').removeClass('is-invalid');
            }

            $(document).on('click', '.btn-edit', function(){
                var btn = $(this);
                setLoadingButton(btn);

                var id = $(this).attr('data-id');
                var master = $(this).attr('master');
                $.ajax({
                    type: 'GET',
                    url: '{{url("")}}/' + master + '/' + id + '/edit',
                    success: function(response){
                        if(response.success){
                            for(var prop in response.data) {
                                $('#' + prop + '-edit').val(response.data[prop]);
                                if($('#' + prop + '-edit').hasClass('select2')){
                                    $('#' + prop + '-edit').trigger('change');
                                }
                            }
                            restoreButton(btn);
                            $('#btn-simpan-edit').attr('data-id', id);
                            $('#modal-edit').modal('show');
                        }
                        else{
                            restoreButton(btn);
                            alert(response.message);
                        }
                    },
                    error: function(response){
                        restoreButton(btn);
                        alert('something went wrong');
                        console.log(response);
                    }
                })
            });

            $('#btn-simpan-tambah').click(function(){
                var btn = $(this);
                setLoadingButton(btn);
                clearErrorMessages();

                var master = btn.attr('master');

                $.ajax({
                    type: 'POST',
                    url: '{{url('')}}/' + master,
                    data: $('#form-tambah').serialize(),
                    success: function(response){
                        if(response.success){
                            location.reload();
                        }
                        else{
                            restoreButton(btn);
                            //alert(response.message);
                            for(var prop in response.errors){
                                $('#' + prop).addClass('is-invalid');
                                $('#' + prop).parent().find('.invalid-feedback').html(response.errors[prop][0]);
                            }
                            console.log(response);
                        }
                    },
                    error: function(response){
                        restoreButton(btn);
                        alert('something went wrong');
                        console.log(response);
                    }
                })
            });

            $('#btn-simpan-edit').click(function(){
                var btn = $(this);
                setLoadingButton(btn);
                clearErrorMessages();

                var master = btn.attr('master');
                var id = btn.attr('data-id');

                $.ajax({
                    type: 'PUT',
                    url: '{{url('')}}/' + master + '/' + id,
                    data: $('#form-edit').serialize(),
                    success: function(response){
                        if(response.success){
                            location.reload();
                        }
                        else{
                            restoreButton(btn);
                            //alert(response.message);
                            for(var prop in response.errors){
                                $('#' + prop + '-edit').addClass('is-invalid');
                                $('#' + prop + '-edit').parent().find('.invalid-feedback').html(response.errors[prop][0]);
                            }
                            console.log(response);
                        }
                    },
                    error: function(response){
                        restoreButton(btn);
                        alert('something went wrong');
                        console.log(response);
                    }
                })
            });

            $(document).on('click', '.btn-delete', function(){
                $('#master').html($(this).attr('master'));
                $('#id_hapus').html($(this).attr('data-id'));
                $('#btn-simpan-hapus').attr('data-id', $(this).attr('data-id'));
                $('#btn-simpan-hapus').attr('master', $(this).attr('master'));


                $('#modal-delete').modal('show');
            });

            $('#btn-simpan-hapus').click(function(){
                var btn = $(this);
                setLoadingButton(btn);

                var master = btn.attr('master');
                var id = btn.attr('data-id');

                $.ajax({
                    type: 'DELETE',
                    url: '{{url("")}}/' + master + '/' + id,
                    success: function(response){
                        if(response.success){
                            location.reload();
                        }
                        else{
                            restoreButton(btn);
                            console.log(response);
                        }
                    },
                    error: function(response){
                        alert('something went wrong');
                        console.log(response);
                    }
                })
            });

            $('.datepicker').daterangepicker({
                timePicker: false,
                singleDatePicker: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
        });
    </script>

    @if(session('status') !== null)
    <script type="text/javascript">
        $(window).on('load',function(){
            //notify2('{{session("status")["message"]}}', '{{session("status")["type"]}}');
            if('{{session("status")["type"]}}' == 'success'){
                toastr.success('{{session("status")["message"]}}');
            }
            else{
                toastr.error('{{session("status")["message"]}}');
            }
        });
    </script>
    @endif

    @yield('script-section')
</body>

</html>

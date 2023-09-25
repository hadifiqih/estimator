<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="icon" href="{{ asset('adminlte') }}/dist/img/antree-logo.png" type="image/png" sizes="16x16">
  <title>Software Antree | Kassab Syariah</title>
  @vite('resources/js/app.js')
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  {{-- Dropzone --}}
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>

  @yield('style')
  {{-- Pusher --}}
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
    navigator.serviceWorker.register('/service-worker.js');
  </script>
  <script>
    var pusher = new Pusher('041462e3ef025d960fee', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('global');
    channel.bind('global-notif', function(data) {
      notif(data);
    });
  </script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="searchTop" name="searchTop">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('auth.logout') }}" role="button">
          <i class="fas fa-door-open" style="color: #ff0000;"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('adminlte') }}/dist/img/antree-logo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8" width="30">
      <span class="brand-text font-weight-light">Antree</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ Auth::user()->employee->photo == null ? asset('adminlte/dist/img/user-kosong.png') :  asset('storage/profile/'. Auth::user()->employee->photo)  }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('employee.show', Auth::user()->id) }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" id="searchSide" name="searchSide">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ url('/dashboard') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Antrian
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if(auth()->user()->role == 'sales' || auth()->user()->employee->can_design == 1 || auth()->user()->role == 'desain')
                <li class="nav-item">
                    <a href="{{ route('design.index') }}" class="nav-link {{ request()->routeIs('design.index') || request()->routeIs('order.edit') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ auth()->user()->role == 'sales' ? 'Submit Project' : 'List Desain' }}</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->role == 'sales')
                <li class="nav-item">
                    <a href="{{ route('antrian.index') }}" class="nav-link {{ request()->routeIs('antrian.index') || request()->routeIs('antrian.edit') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Order</p>
                    </a>
                </li>
                @endif

                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising' || auth()->user()->role == 'dokumentasi' || auth()->user()->role == 'supervisor')
                <li class="nav-item">
                    <a href="{{ route('antrian.index') }}" class="nav-link {{ request()->routeIs('antrian.index') || request()->routeIs('antrian.edit') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List Pekerjaan</p>
                    </a>
                </li>
                @endif

                @if(auth()->user()->role == 'admin')
                {{-- Memilih tanggal untuk Unduh Laporan Workshop --}}
                <li class="nav-item">
                    <a href="{{ route('laporan.workshop') }}" class="nav-link {{ request()->routeIs('laporan.workshop') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Laporan Workshop</p>
                    </a>
                </li>
                @endif

                @if(auth()->user()->role == 'estimator')
                <li class="nav-item">
                    <a href="{{ route('estimator.index') }}" class="nav-link {{ request()->routeIs('estimator.index') || request()->routeIs('estimator.edit') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Estimator</p>
                    </a>
                </li>
                @endif

                @if(auth()->user()->role == 'sales')
                <li class="nav-item">
                    <a href="{{ route('report.sales') }}" class="nav-link {{ request()->routeIs('report.sales') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ringkasan Penjualan</p>
                    </a>
                </li>
                @endif
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('page')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">@yield('page')</a></li>
              <li class="breadcrumb-item active">@yield('breadcrumb')</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="#">by Kassab Syariah</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Tanggal : </b> {{ date('d F Y') }}
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('adminlte') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('adminlte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/jszip/jszip.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

{{-- BS-Custom-Input-File --}}
<script src="{{ asset('adminlte') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<!-- AdminLTE -->
<script src="{{ asset('adminlte') }}/dist/js/adminlte.js"></script>

{{-- DayJS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/dayjs.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script>

    function notif(data) {
        if(data.message.title == 'Antrian Workshop') {
            $(document).Toasts('create', {
            class: 'bg-warning',
            body: data.message.body,
            title: data.message.title,
            icon: 'fas fa-envelope fa-lg',
            });
        }else if(data.message.title == 'Antrian Desain') {
            $(document).Toasts('create', {
            class: 'bg-info',
            body: data.message.body,
            title: data.message.title,
            icon: 'fas fa-envelope fa-lg',
            });
        }

    };
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
<script>
    const beamsClient = new PusherPushNotifications.Client({
      instanceId: '0958376f-0b36-4f59-adae-c1e55ff3b848',
    });

    const tokenProvider = new PusherPushNotifications.TokenProvider({
      url: "{{ route('beams.auth') }}"
    });

    //stop the SDK from automatically connecting to Beams
    beamsClient.stop();

    beamsClient.start()
    .then(() => beamsClient.clearAllState()) // clear state on start
    .then(() => console.log('Successfully registered and subscribed to Beams!'))
    .then(() => beamsClient.setUserId('user-{{ Auth::user()->id }}', tokenProvider))
    .then(() => beamsClient.getUserId())
    .then(userId => console.log('Successfully registered and subscribed!', userId))
    .then(() =>

    @if(Auth::user()->role == 'sales')
    beamsClient.setDeviceInterests(['hello' , 'sales'])
    @elseif(Auth::user()->role == 'admin')
    beamsClient.setDeviceInterests(['hello' , 'admin'])
    @elseif(Auth::user()->role == 'stempel' || auth()->user()->role == 'advertising')
    beamsClient.setDeviceInterests(['hello' , 'operator'])
    @elseif(Auth::user()->role == 'supervisor')
    beamsClient.setDeviceInterests(['hello' , 'supervisor'])
    @elseif(Auth::user()->role == 'estimator')
    beamsClient.setDeviceInterests(['hello', 'operator'])
    @elseif(Auth::user()->role == 'desain' || Auth::user()->employee->can_design == 1)
    beamsClient.setDeviceInterests(['hello' , 'desain'])
    @else
    beamsClient.setDeviceInterests(['hello'])
    @endif
    )
    .then(() => beamsClient.getDeviceInterests())
    .then(interests => console.log('Successfully registered and subscribed!', interests))
    .catch(console.error);

</script>

@yield('script')
<script>
    function sendReminder() {
            $.ajax({
                type: "GET",
                url: "{{ route('antrian.reminder') }}",
                success: function (response) {
                    console.log(response);
                }
            })
        }
    $(document).ready(function() {
        var targetWaktu = '16:45'

        var interval = 60000;

        function checkTime(){
            var waktuSekarang = dayjs().format('HH:mm');
            if(waktuSekarang == targetWaktu){
                sendReminder();
            }
        }

        setInterval(checkTime, interval);
    });
</script>

</body>
</html>

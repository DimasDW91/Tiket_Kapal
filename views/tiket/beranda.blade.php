<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard — Tiket Kapal Laut</title>

    <link href="{{ url('frontend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
    <link href="{{ url('frontend/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">

        {{-- ═══════════════════════════════════════ SIDEBAR ═══════════════════════════════════ --}}
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('beranda') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-ship"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Tiket Kapal</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="{{ route('beranda') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Manajemen</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kapal.index') }}">
                    <i class="fas fa-fw fa-ship"></i>
                    <span>Kapal</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-anchor"></i>
                    <span>Pelabuhan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('jadwal.index') }}">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Jadwal</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Transaksi</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('penumpang.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Penumpang</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('pemesanan.index') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Pemesanan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('tiket.index') }}">
                    <i class="fas fa-fw fa-ticket-alt"></i>
                    <span>Tiket</span>
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Admin</div>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        {{-- ═══════════════════════════════════════ END SIDEBAR ═══════════════════════════════ --}}

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                {{-- ═════════════════════════════════ TOPBAR ══════════════════════════════════ --}}
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    {{-- Sesi Kasir Alert --}}
                    @if(Auth::user()->role === 'kasir')
                        @if($sesiAktif)
                            <span class="badge badge-success ml-2 px-3 py-2">
                                <i class="fas fa-circle fa-xs"></i>
                                Sesi Buka sejak {{ $sesiAktif->waktu_buka->format('H:i') }}
                            </span>
                        @else
                            <span class="badge badge-warning ml-2 px-3 py-2">
                                <i class="fas fa-exclamation-circle fa-xs"></i>
                                Sesi Belum Dibuka
                            </span>
                        @endif
                    @endif

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->name }}
                                    <span class="badge badge-primary ml-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{ url('frontend/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>
                {{-- ═════════════════════════════════ END TOPBAR ══════════════════════════════ --}}

                {{-- ═════════════════════════════════ MAIN CONTENT ════════════════════════════ --}}
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <small class="text-muted">{{ now()->translatedFormat('l, d F Y — H:i') }}</small>
                    </div>

                    {{-- ──────────────── KARTU STATISTIK UTAMA ──────────────── --}}
                    <div class="row">

                        {{-- Jumlah Kapal --}}
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Kapal
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($jumlahKapal) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-ship fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                    <a href="#" class="text-xs text-primary font-weight-bold">
                                        Lihat Detail <i class="fas fa-arrow-right fa-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Jumlah Penumpang --}}
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Penumpang
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($jumlahPenumpang) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                    <a href="#" class="text-xs text-success font-weight-bold">
                                        Lihat Detail <i class="fas fa-arrow-right fa-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Jumlah Pemesanan --}}
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Pemesanan
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($jumlahPemesanan) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                    <a href="#" class="text-xs text-info font-weight-bold">
                                        Lihat Detail <i class="fas fa-arrow-right fa-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Jumlah Tiket --}}
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Tiket
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($jumlahTiket) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                    <a href="#" class="text-xs text-warning font-weight-bold">
                                        Lihat Detail <i class="fas fa-arrow-right fa-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- END KARTU UTAMA --}}

                    {{-- ──────────────── STATISTIK HARI INI ──────────────── --}}
                    <div class="row">

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Pemesanan Hari Ini
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($pemesananHariIni) }}
                                                <small class="text-muted font-weight-normal" style="font-size:.65rem">transaksi</small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Pendapatan Hari Ini
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- END STATISTIK HARI INI --}}

                    {{-- ──────────────── INFO SESI KASIR (hanya kasir) ──────────────── --}}
                    @if(Auth::user()->role === 'kasir')
                    <div class="row">
                        <div class="col-12 mb-4">
                            @if($sesiAktif)
                                <div class="card border-left-success shadow">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-success mr-2"><i class="fas fa-circle fa-xs"></i> Sesi Aktif</span>
                                                <strong>Dibuka:</strong> {{ $sesiAktif->waktu_buka->format('d/m/Y H:i') }}
                                                &nbsp;|&nbsp;
                                                <strong>Transaksi:</strong> {{ $sesiAktif->jumlah_transaksi }} pesanan
                                                &nbsp;|&nbsp;
                                                <strong>Total:</strong> Rp {{ number_format($sesiAktif->total_transaksi, 0, ',', '.') }}
                                            </div>
                                            <a href="#" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-door-open mr-1"></i> Tutup Sesi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card border-left-warning shadow">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge badge-warning mr-2"><i class="fas fa-exclamation-triangle fa-xs"></i> Belum Ada Sesi</span>
                                                Buka sesi kasir sebelum memulai transaksi.
                                            </div>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-play mr-1"></i> Buka Sesi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    {{-- END SESI KASIR --}}

                </div>
                {{-- END MAIN CONTENT --}}

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Sistem Tiket Kapal Laut &copy; {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Logout Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin keluar dari sistem?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('frontend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ url('frontend/js/sb-admin-2.min.js') }}"></script>

</body>
</html>

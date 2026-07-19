<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah Pelabuhan — Tiket Kapal Laut</title>
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

            <li class="nav-item">
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

            <li class="nav-item active">
                <a class="nav-link" href="{{ route('pelabuhan.index') }}">
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
                                <img class="img-profile rounded-circle" src="{{ url('frontend/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                {{-- ═════════════════════════════════ END TOPBAR ══════════════════════════════ --}}

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Pelabuhan</h1>
                        <a href="{{ route('pelabuhan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left fa-sm"></i> Kembali
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pelabuhan</h6>
                                </div>
                                <div class="card-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('pelabuhan.store') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="kode_pelabuhan">Kode Pelabuhan <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="kode_pelabuhan"
                                                   id="kode_pelabuhan"
                                                   class="form-control @error('kode_pelabuhan') is-invalid @enderror"
                                                   value="{{ old('kode_pelabuhan') }}"
                                                   required>
                                            @error('kode_pelabuhan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nama_pelabuhan">Nama Pelabuhan <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="nama_pelabuhan"
                                                   id="nama_pelabuhan"
                                                   class="form-control @error('nama_pelabuhan') is-invalid @enderror"
                                                   value="{{ old('nama_pelabuhan') }}"
                                                   required>
                                            @error('nama_pelabuhan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="kota">Kota <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   name="kota"
                                                   id="kota"
                                                   class="form-control @error('kota') is-invalid @enderror"
                                                   value="{{ old('kota') }}"
                                                   required>
                                            @error('kota')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                            <textarea name="alamat"
                                                      id="alamat"
                                                      rows="3"
                                                      class="form-control @error('alamat') is-invalid @enderror"
                                                      required>{{ old('alamat') }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <a href="{{ route('pelabuhan.index') }}" class="btn btn-secondary ml-2">Batal</a>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
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

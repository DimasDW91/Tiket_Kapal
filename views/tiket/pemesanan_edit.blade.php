<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Pemesanan — Tiket Kapal Laut</title>
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

            <li class="nav-item">
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

            <li class="nav-item active">
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
                        <h1 class="h3 mb-0 text-gray-800">Edit Pemesanan</h1>
                        <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left fa-sm"></i> Kembali
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Pemesanan</h6>
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

                                    <form method="POST" action="{{ route('pemesanan.update', $pemesanan->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="kode_booking" class="form-label">Kode Booking</label>
                                            <input type="text"
                                                   class="form-control @error('kode_booking') is-invalid @enderror"
                                                   id="kode_booking"
                                                   name="kode_booking"
                                                   value="{{ old('kode_booking', $pemesanan->kode_booking) }}"
                                                   required>
                                            @error('kode_booking')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="jadwal_id" class="form-label">Jadwal Keberangkatan</label>
                                            <select name="jadwal_id"
                                                    id="jadwal_id"
                                                    class="form-control @error('jadwal_id') is-invalid @enderror"
                                                    required>
                                                <option value="">-- Pilih Jadwal --</option>
                                                @foreach ($jadwalList as $jadwal)
                                                    <option value="{{ $jadwal->id }}"
                                                        {{ old('jadwal_id', $pemesanan->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                                                        {{ optional($jadwal->kapal)->nama_kapal ?? '-' }} —
                                                        {{ optional($jadwal->pelabuhanAsal)->nama_pelabuhan ?? '-' }}
                                                        &rarr;
                                                        {{ optional($jadwal->pelabuhanTujuan)->nama_pelabuhan ?? '-' }}
                                                        ({{ optional($jadwal->tanggal_berangkat)->format('d/m/Y H:i') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jadwal_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                                            <input type="text"
                                                   class="form-control @error('nama_pemesan') is-invalid @enderror"
                                                   id="nama_pemesan"
                                                   name="nama_pemesan"
                                                   value="{{ old('nama_pemesan', $pemesanan->nama_pemesan) }}"
                                                   required>
                                            @error('nama_pemesan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="no_hp_pemesan" class="form-label">No. HP Pemesan</label>
                                            <input type="text"
                                                   class="form-control @error('no_hp_pemesan') is-invalid @enderror"
                                                   id="no_hp_pemesan"
                                                   name="no_hp_pemesan"
                                                   value="{{ old('no_hp_pemesan', $pemesanan->no_hp_pemesan) }}"
                                                   required>
                                            @error('no_hp_pemesan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="channel" class="form-label">Channel</label>
                                            <select name="channel"
                                                    id="channel"
                                                    class="form-control @error('channel') is-invalid @enderror"
                                                    required>
                                                <option value="">-- Pilih Channel --</option>
                                                <option value="kasir" {{ old('channel', $pemesanan->channel) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                <option value="online" {{ old('channel', $pemesanan->channel) == 'online' ? 'selected' : '' }}>Online</option>
                                            </select>
                                            @error('channel')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="tanggal_pesan" class="form-label">Tanggal Pesan</label>
                                            <input type="datetime-local"
                                                   class="form-control @error('tanggal_pesan') is-invalid @enderror"
                                                   id="tanggal_pesan"
                                                   name="tanggal_pesan"
                                                   value="{{ old('tanggal_pesan', optional($pemesanan->tanggal_pesan)->format('Y-m-d\TH:i')) }}"
                                                   required>
                                            @error('tanggal_pesan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="jumlah_tiket" class="form-label">Jumlah Tiket</label>
                                            <input type="number"
                                                   min="1"
                                                   class="form-control @error('jumlah_tiket') is-invalid @enderror"
                                                   id="jumlah_tiket"
                                                   name="jumlah_tiket"
                                                   value="{{ old('jumlah_tiket', $pemesanan->jumlah_tiket) }}"
                                                   required>
                                            @error('jumlah_tiket')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_harga" class="form-label">Total Harga (Rp)</label>
                                            <input type="number"
                                                   min="0"
                                                   step="0.01"
                                                   class="form-control @error('total_harga') is-invalid @enderror"
                                                   id="total_harga"
                                                   name="total_harga"
                                                   value="{{ old('total_harga', $pemesanan->total_harga) }}"
                                                   required>
                                            @error('total_harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status_pemesanan" class="form-label">Status Pemesanan</label>
                                            <select name="status_pemesanan"
                                                    id="status_pemesanan"
                                                    class="form-control @error('status_pemesanan') is-invalid @enderror"
                                                    required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="pending" {{ old('status_pemesanan', $pemesanan->status_pemesanan) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="dibayar" {{ old('status_pemesanan', $pemesanan->status_pemesanan) == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                                <option value="dibatalkan" {{ old('status_pemesanan', $pemesanan->status_pemesanan) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                            @error('status_pemesanan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                        <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary ml-2">Batal</a>
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

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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

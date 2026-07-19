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
            <li class="nav-item active">
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
                        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col">
                            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus fa-sm"></i> Tambah User
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="datatablesSimple" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>No. HP</th>
                                                    <th>Role</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>{{ $item->no_hp ?: '-' }}</td>
                                                        <td>
                                                            @php
                                                                $badgeClass = match($item->role) {
                                                                    'admin' => 'badge-danger',
                                                                    'kasir' => 'badge-primary',
                                                                    'pelanggan' => 'badge-secondary',
                                                                    default => 'badge-secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($item->role) }}</span>
                                                        </td>
                                                        <td>

                                                            <!-- Tombol Edit -->
                                                            <a href="{{ route('users.edit', $item->id) }}"
                                                               class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>

                                                            <!-- Tombol Hapus -->
                                                            @if($item->id !== Auth::id())
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#modalHapus"
                                                                    data-id="{{ $item->id }}"
                                                                    data-nama="{{ $item->name }}">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus pengguna <strong id="namaUser"></strong>?
                    Pengguna yang masih memiliki data transaksi tidak dapat dihapus.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form id="formHapus" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        // Isi form hapus & nama user sesuai baris yang dipilih
        $('#modalHapus').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const nama = button.data('nama');

            const form = document.getElementById('formHapus');
            form.action = "{{ url('users') }}/" + id;

            document.getElementById('namaUser').textContent = nama;
        });
    </script>

</body>
</html>

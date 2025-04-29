@extends('layouts_pengunjung.main')
@section('title', 'Daftar Alamat')

@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ route('profile.index') }}">Profil</a>
                    <span class="breadcrumb-item active">Daftar Alamat</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-10 mx-auto">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                        <h5 class="text-dark mb-0">Daftar Alamat</h5>
                        <a href="{{ route('profile.addresses.add') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Alamat Baru
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($addresses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Label</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($addresses as $address)
                                            <tr>
                                                <td>{{ $address->keterangan }}</td>
                                                <td>{{ $address->alamat }}</td>
                                                <td>
                                                    @if ($address->is_primary)
                                                        <span class="badge badge-success">Alamat Utama</span>
                                                    @else
                                                        <span class="badge badge-secondary">Alamat Tambahan</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('profile.addresses.edit', $address->id) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        @if (!$address->is_primary)
                                                            <form
                                                                action="{{ route('profile.addresses.set-primary', $address->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-check"></i> Jadikan Utama
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if ($addresses->count() > 1)
                                                            <form
                                                                action="{{ route('profile.addresses.delete', $address->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i> Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                                <h5>Anda belum menambahkan alamat</h5>
                                <p class="text-muted">Klik tombol "Tambah Alamat Baru" untuk menambahkan alamat pengiriman.
                                </p>
                            </div>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts_pengunjung.main')
@section('title', 'Profil Saya')

@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <span class="breadcrumb-item active">Profil</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-12 mx-auto">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary">
                                <h5 class="text-dark mb-0">Informasi Profil</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Nama</div>
                                    <div class="col-md-8">{{ $user->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Username</div>
                                    <div class="col-md-8">{{ $user->username }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Email</div>
                                    <div class="col-md-8">{{ $user->email }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">No. Telepon</div>
                                    <div class="col-md-8">{{ $customer->telp ?? '-' }}</div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary">
                                <h5 class="text-dark mb-0">Ubah Password</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.update-password') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="current_password">Password Saat Ini</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Perbarui Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <h5 class="text-dark mb-0">Alamat Saya</h5>
                    </div>
                    <div class="card-body">
                        @if ($addresses->count() > 0)
                            @foreach ($addresses as $address)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $address->keterangan }}</h6>
                                        @if ($address->is_primary)
                                            <span class="badge badge-success">Alamat Utama</span>
                                        @endif
                                    </div>
                                    <p class="mb-2">{{ $address->alamat }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Anda belum menambahkan alamat.</p>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('profile.addresses') }}" class="btn btn-primary">
                                <i class="fas fa-map-marker-alt"></i> Kelola Alamat
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

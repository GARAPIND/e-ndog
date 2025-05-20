@extends('layouts_pengunjung.main')
@section('title', 'Pesanan Anda')

@section('content')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #ffc800;
            color: #000000;
            border-radius: 0.25rem;
        }

        .nav-tabs .nav-link {
            color: #495057;
        }
    </style>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <span class="breadcrumb-item active">Pesanan</span>
                </nav>
            </div>

            <div class="col-12">
                <ul class="nav nav-tabs nav-justified mb-3" id="pesananTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="belum-bayar-tab" data-toggle="tab" href="#belum-bayar"
                            role="tab">Belum Bayar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dikemas-tab" data-toggle="tab" href="#dikemas" role="tab">Dikemas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dikirim-tab" data-toggle="tab" href="#dikirim" role="tab">Dikirim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab">Selesai</a>
                    </li>
                </ul>


                <div class="tab-content" id="pesananTabsContent">
                    <div class="tab-pane fade show active" id="belum-bayar" role="tabpanel">

                    </div>
                    <div class="tab-pane fade" id="dikemas" role="tabpanel">

                    </div>

                    <div class="tab-pane fade" id="dikirim" role="tabpanel">

                    </div>
                    <div class="tab-pane fade" id="selesai" role="tabpanel">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-VizuQvv54xcuk1Yg"></script>

    <script>
        $(document).ready(function() {
            get_data_pesanan_belum();
            get_data_pesanan_dikemas();
            get_data_pesanan_dikirim();
            get_data_pesanan_selesai();
        })

        function get_data_pesanan_belum() {
            $.ajax({
                url: "{{ route('pesanan.get_data_pesanan') }}",
                type: 'get',
                data: {
                    status_pengiriman: "Menunggu Pembayaran"
                },
                beforeSend: () => {
                    $('#belum-bayar').html(`
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading data pesanan...</div>
                    </div>
                </div>
            `);
                },
                success: (response) => {
                    let html = '';
                    if (response.length === 0) {
                        html += `<div class="row mt-2">
                                    <div class="col-12 text-center">
                                        <h5 class="text-muted">Tidak Ada Pesanan</h5>
                                    </div>
                                </div>`;
                    } else {
                        response.forEach(item => {
                            html += `<div class="card p-3 mb-3 rounded">
                            <div class="d-flex">
                                <img src="/storage/foto-produk/${item.detail[0].produk.foto}" alt="VOOVA Tas Laptop" width="100"
                                    class="mr-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.detail[0].produk.nama}</h6>
                                    <small>Jumlah: ${item.detail[0].jumlah}</small>
                                    <div class="mt-2">
                                        ${item.detail[0].produk.harga_diskon ? `
                                                                                                                                <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(item.detail[0].produk.harga)}</del></span>
                                                                                                                                <span class="font-weight-bold text-danger ml-2">${rupiahFormat(item.detail[0].produk.harga_diskon)}</span>
                                                                                                                                ` : `
                                                                                                                                <span class="font-weight-bold text-success">${rupiahFormat(item.detail[0].produk.harga)}</span>
                                                                                                                                `}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total ${item.detail[0].jumlah} produk:</strong>
                                        <span class="text-success">${rupiahFormat(item.detail[0].sub_total)}</span>
                                    </div>
                                </div>
                            </div>`

                            if (item.detail.length > 1) {
                                html += `<div class="mt-3 text-center">
                                        <a href="javascript:void(0)" class="btn btn-light" data-toggle="collapse"
                                            data-target="#accordionProduk${item.id}">
                                            Lihat Produk lainnya >>
                                        </a>
                                    </div>
                                    <div id="accordionProduk${item.id}" class="collapse mt-3">`;

                                item.detail.slice(1).forEach(function(detail) {
                                    html += `
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <img src="/storage/foto-produk/${detail.produk.foto}" alt="${detail.produk.nama || 'Produk'}"
                                                            width="100" class="mr-3">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">${detail.produk.nama}</h6>
                                                            <small>Jumlah: ${detail.jumlah}</small>
                                                            <div class="mt-2">
                                                                ${detail.produk.harga_diskon ? `
                                                                                                                                                    <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(detail.produk.harga)}</del></span>
                                                                                                                                                    <span class="font-weight-bold text-danger ml-2">${rupiahFormat(detail.produk.harga_diskon)}</span>
                                                                                                                                                    ` : `
                                                                                                                                                    <span class="font-weight-bold text-success">${rupiahFormat(detail.produk.harga)}</span>
                                                                                                                                                    `}
                                                            </div>
                                                            <div class="mt-2">
                                                                <strong>Total ${detail.jumlah} produk:</strong>
                                                                <span class="text-success">${rupiahFormat(detail.sub_total)}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                html += `</div>`;
                            }

                            html += `<div class="bg-secondary mt-3 p-2 rounded">
                                <span class="text-danger font-weight-bold">Pesanan belum dibayar</span><br>
                                <small class="text-muted">Segera bayar pesanan agar segera di kemas oleh toko</small>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="bayar_ulang(${item.id})">
                                    <i class="fas fa-credit-card"></i> Bayar Pesanan
                                </a>
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Hapus Pesanan
                                </a>
                            </div>
                        </div>`;
                        });
                    }
                    $('#belum-bayar').html(html);
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            })
        }

        function bayar_ulang(id) {
            $.ajax({
                url: "{{ route('pesanan.bayar_ulang') }}",
                type: 'get',
                data: {
                    id: id
                },
                beforeSend: () => {
                    Notiflix.Loading.hourglass('Loading...');
                },
                success: (response) => {
                    Notiflix.Loading.remove();

                    if (response.status === 'success') {
                        payWithMidtrans(response.snap_token, response.order_id);
                    } else {
                        Notiflix.Notify.failure(response.message || 'Transaksi gagal.');
                    }
                },
                error: (xhr) => {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure(xhr.responseText || 'Terjadi kesalahan.');
                }
            })
        }

        function payWithMidtrans(snapToken, order_id) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Notiflix.Notify.success('Pembayaran Berhasil');
                    const finishRedirectUrl = '/belanja/sukses';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onPending: function(result) {
                    Notiflix.Notify.warning('Pembayaran Pending!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onError: function(result) {
                    Notiflix.Notify.failure('Terjadi kesalahan pada pembayaran!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = result.order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                },
                onClose: function() {
                    Notiflix.Notify.failure('Pembayaran Ditunda!');
                    const finishRedirectUrl = '/belanja/gagal';
                    const orderId = order_id;
                    window.location.href = `${finishRedirectUrl}/${orderId}`;
                }
            });
        }

        function get_data_pesanan_dikemas() {
            $.ajax({
                url: "{{ route('pesanan.get_data_pesanan') }}",
                type: 'get',
                data: {
                    status_pengiriman: "Dikemas"
                },
                beforeSend: () => {
                    $('#dikemas').html(`
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading data pesanan...</div>
                    </div>
                </div>
            `);
                },
                success: (response) => {
                    let html = '';
                    if (response.length === 0) {
                        html += `<div class="row mt-2">
                                    <div class="col-12 text-center">
                                        <h5 class="text-muted">Tidak Ada Pesanan</h5>
                                    </div>
                                </div>`;
                    } else {
                        response.forEach(item => {
                            html += `<div class="card p-3 mb-3 rounded">
                            <div class="d-flex">
                                <img src="/storage/foto-produk/${item.detail[0].produk.foto}" alt="VOOVA Tas Laptop" width="100"
                                    class="mr-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.detail[0].produk.nama}</h6>
                                    <small>Jumlah: ${item.detail[0].jumlah}</small>
                                    <div class="mt-2">
                                        ${item.detail[0].produk.harga_diskon ? `
                                                                                                                                <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(item.detail[0].produk.harga)}</del></span>
                                                                                                                                <span class="font-weight-bold text-danger ml-2">${rupiahFormat(item.detail[0].produk.harga_diskon)}</span>
                                                                                                                                ` : `
                                                                                                                                <span class="font-weight-bold text-success">${rupiahFormat(item.detail[0].produk.harga)}</span>
                                                                                                                                `}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total ${item.detail[0].jumlah} produk:</strong>
                                        <span class="text-success">${rupiahFormat(item.detail[0].sub_total)}</span>
                                    </div>
                                </div>
                            </div>`

                            if (item.detail.length > 1) {
                                html += `<div class="mt-3 text-center">
                                        <a href="javascript:void(0)" class="btn btn-light" data-toggle="collapse"
                                            data-target="#accordionProduk${item.id}">
                                            Lihat Produk lainnya >>
                                        </a>
                                    </div>
                                    <div id="accordionProduk${item.id}" class="collapse mt-3">`;

                                item.detail.slice(1).forEach(function(detail) {
                                    html += `
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <img src="/storage/foto-produk/${detail.produk.foto}" alt="${detail.produk.nama || 'Produk'}"
                                                            width="100" class="mr-3">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">${detail.produk.nama}</h6>
                                                            <small>Jumlah: ${detail.jumlah}</small>
                                                            <div class="mt-2">
                                                                ${detail.produk.harga_diskon ? `
                                                                                                                                                    <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(detail.produk.harga)}</del></span>
                                                                                                                                                    <span class="font-weight-bold text-danger ml-2">${rupiahFormat(detail.produk.harga_diskon)}</span>
                                                                                                                                                    ` : `
                                                                                                                                                    <span class="font-weight-bold text-success">${rupiahFormat(detail.produk.harga)}</span>
                                                                                                                                                    `}
                                                            </div>
                                                            <div class="mt-2">
                                                                <strong>Total ${detail.jumlah} produk:</strong>
                                                                <span class="text-success">${rupiahFormat(detail.sub_total)}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                html += `</div>`;
                            }

                            html += `<div class="bg-secondary mt-3 p-2 rounded">
                                <span class="text-info font-weight-bold">Pesanan dikemas</span><br>
                                <small class="text-muted">Harap tunggu 1-3 hari untuk pengemasan barang</small>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>`;
                        });
                    }
                    $('#dikemas').html(html);
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            })
        }

        function get_data_pesanan_dikirim() {
            $.ajax({
                url: "{{ route('pesanan.get_data_pesanan') }}",
                type: 'get',
                data: {
                    status_pengiriman: "Dikirim"
                },
                beforeSend: () => {
                    $('#dikirim').html(`
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading data pesanan...</div>
                    </div>
                </div>
            `);
                },
                success: (response) => {
                    let html = '';
                    if (response.length === 0) {
                        html += `<div class="row mt-2">
                                    <div class="col-12 text-center">
                                        <h5 class="text-muted">Tidak Ada Pesanan</h5>
                                    </div>
                                </div>`;
                    } else {
                        response.forEach(item => {
                            html += `<div class="card p-3 mb-3 rounded">
                            <div class="d-flex">
                                <img src="/storage/foto-produk/${item.detail[0].produk.foto}" alt="VOOVA Tas Laptop" width="100"
                                    class="mr-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.detail[0].produk.nama}</h6>
                                    <small>Jumlah: ${item.detail[0].jumlah}</small>
                                    <div class="mt-2">
                                        ${item.detail[0].produk.harga_diskon ? `
                                                                                                                                <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(item.detail[0].produk.harga)}</del></span>
                                                                                                                                <span class="font-weight-bold text-danger ml-2">${rupiahFormat(item.detail[0].produk.harga_diskon)}</span>
                                                                                                                                ` : `
                                                                                                                                <span class="font-weight-bold text-success">${rupiahFormat(item.detail[0].produk.harga)}</span>
                                                                                                                                `}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total ${item.detail[0].jumlah} produk:</strong>
                                        <span class="text-success">${rupiahFormat(item.detail[0].sub_total)}</span>
                                    </div>
                                </div>
                            </div>`

                            if (item.detail.length > 1) {
                                html += `<div class="mt-3 text-center">
                                        <a href="javascript:void(0)" class="btn btn-light" data-toggle="collapse"
                                            data-target="#accordionProduk${item.id}">
                                            Lihat Produk lainnya >>
                                        </a>
                                    </div>
                                    <div id="accordionProduk${item.id}" class="collapse mt-3">`;

                                item.detail.slice(1).forEach(function(detail) {
                                    html += `
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <img src="/storage/foto-produk/${detail.produk.foto}" alt="${detail.produk.nama || 'Produk'}"
                                                            width="100" class="mr-3">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">${detail.produk.nama}</h6>
                                                            <small>Jumlah: ${detail.jumlah}</small>
                                                            <div class="mt-2">
                                                                ${detail.produk.harga_diskon ? `
                                                                                                                                                    <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(detail.produk.harga)}</del></span>
                                                                                                                                                    <span class="font-weight-bold text-danger ml-2">${rupiahFormat(detail.produk.harga_diskon)}</span>
                                                                                                                                                    ` : `
                                                                                                                                                    <span class="font-weight-bold text-success">${rupiahFormat(detail.produk.harga)}</span>
                                                                                                                                                    `}
                                                            </div>
                                                            <div class="mt-2">
                                                                <strong>Total ${detail.jumlah} produk:</strong>
                                                                <span class="text-success">${rupiahFormat(detail.sub_total)}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                html += `</div>`;
                            }

                            html += `<div class="bg-secondary mt-3 p-2 rounded">
                                <span class="text-dark font-weight-bold">Pesanan dikirim</span><br>
                                <small class="text-muted">Harap klik "pesanan selesai" jika sudah sampai di alamat
                                    anda</small>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="javascript:void(0)" class="btn btn-success btn-sm">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </a>
                            </div>
                        </div>`;
                        });
                    }
                    $('#dikirim').html(html);
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            })
        }

        function get_data_pesanan_selesai() {
            $.ajax({
                url: "{{ route('pesanan.get_data_pesanan') }}",
                type: 'get',
                data: {
                    status_pengiriman: "Selesai"
                },
                beforeSend: () => {
                    $('#selesai').html(`
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading data pesanan...</div>
                    </div>
                </div>
            `);
                },
                success: (response) => {
                    let html = '';
                    if (response.length === 0) {
                        html += `<div class="row mt-2">
                                    <div class="col-12 text-center">
                                        <h5 class="text-muted">Tidak Ada Pesanan</h5>
                                    </div>
                                </div>`;
                    } else {
                        response.forEach(item => {
                            html += `<div class="card p-3 mb-3 rounded">
                            <div class="d-flex">
                                <img src="/storage/foto-produk/${item.detail[0].produk.foto}" alt="VOOVA Tas Laptop" width="100"
                                    class="mr-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.detail[0].produk.nama}</h6>
                                    <small>Jumlah: ${item.detail[0].jumlah}</small>
                                    <div class="mt-2">
                                        ${item.detail[0].produk.harga_diskon ? `
                                                                                                                            <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(item.detail[0].produk.harga)}</del></span>
                                                                                                                            <span class="font-weight-bold text-danger ml-2">${rupiahFormat(item.detail[0].produk.harga_diskon)}</span>
                                                                                                                            ` : `
                                                                                                                            <span class="font-weight-bold text-success">${rupiahFormat(item.detail[0].produk.harga)}</span>
                                                                                                                            `}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total ${item.detail[0].jumlah} produk:</strong>
                                        <span class="text-success">${rupiahFormat(item.detail[0].sub_total)}</span>
                                    </div>
                                </div>
                            </div>`

                            if (item.detail.length > 1) {
                                html += `<div class="mt-3 text-center">
                                        <a href="javascript:void(0)" class="btn btn-light" data-toggle="collapse"
                                            data-target="#accordionProduk${item.id}">
                                            Lihat Produk lainnya >>
                                        </a>
                                    </div>
                                    <div id="accordionProduk${item.id}" class="collapse mt-3">`;

                                item.detail.slice(1).forEach(function(detail) {
                                    html += `
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <img src="/storage/foto-produk/${detail.produk.foto}" alt="${detail.produk.nama || 'Produk'}"
                                                            width="100" class="mr-3">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">${detail.produk.nama}</h6>
                                                            <small>Jumlah: ${detail.jumlah}</small>
                                                            <div class="mt-2">
                                                                ${detail.produk.harga_diskon ? `
                                                                                                                                                <span class="text-muted text-decoration-line-through"><del>${rupiahFormat(detail.produk.harga)}</del></span>
                                                                                                                                                <span class="font-weight-bold text-danger ml-2">${rupiahFormat(detail.produk.harga_diskon)}</span>
                                                                                                                                                ` : `
                                                                                                                                                <span class="font-weight-bold text-success">${rupiahFormat(detail.produk.harga)}</span>
                                                                                                                                                `}
                                                            </div>
                                                            <div class="mt-2">
                                                                <strong>Total ${detail.jumlah} produk:</strong>
                                                                <span class="text-success">${rupiahFormat(detail.sub_total)}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                });

                                html += `</div>`;
                            }

                            html += `<div class="bg-secondary mt-3 p-2 rounded">
                                <span class="text-success font-weight-bold">Pesanan selesai</span><br>
                                <small class="text-muted">Terima kasih sudah berbelanja di toko kami</small>
                            </div>
                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>  `;
                        });
                    }
                    $('#selesai').html(html);
                },
                error: ({
                    responseText
                }) => {
                    Notiflix.Notify.failure(responseText);
                }
            })
        }
    </script>
@endsection

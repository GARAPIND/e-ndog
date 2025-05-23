@extends('layouts_pengunjung.main')
@section('title', 'List Produk')

@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('dashboard.pengunjung') }}">Home</a>
                    <span class="breadcrumb-item active">Produk List</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter
                        berdasarkan harga</span></h5>
                <div class="bg-light p-4 mb-30">
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-1" value="0-20000">
                        <label class="custom-control-label" for="price-1">Rp 0 - Rp 20.000</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-2" value="20000-50000">
                        <label class="custom-control-label" for="price-2">Rp 20.000 - Rp 50.000</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-3" value="50000-100000">
                        <label class="custom-control-label" for="price-3">Rp 50.000 - Rp 100.000</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-4" value="100000">
                        <label class="custom-control-label" for="price-4">> Rp 100.000</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-3" onclick="filter_data()">
                        <i class="fas fa-filter"></i> Filter Produk
                    </button>
                </div>
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="row pb-3" id="data_produk_bar"></div>
                <div class="row pb-3" id="data_produk_list"></div>
                <div id="pagination"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#data_produk_list').hide();
            filter_data();
        })

        function filter_data(page = 1) {
            const checkboxes = document.querySelectorAll('.custom-control-input:checked');
            let selectedValues = [];
            checkboxes.forEach(function(checkbox) {
                selectedValues.push(checkbox.value);
            });

            get_data_produk(1, selectedValues);
        }

        function get_data_produk(page = 1, selectedValues = []) {
            $.ajax({
                url: "{{ route('produk.get_data') }}",
                type: 'get',
                data: {
                    page: page,
                    filters: selectedValues,
                },
                beforeSend: () => {
                    $('#data_produk_bar').html(`
                        <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>Loading data produk...</div>
                            </div>
                        </div>
                    `);
                    $('#data_produk_list').html(`
                        <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <div>Loading data produk...</div>
                            </div>
                        </div>
                    `);
                },
                success: (response) => {
                    set_html_produk_bar(response.data);
                    set_html_produk_list(response.data);
                    render_pagination_bar(response.current_page, response.total_pages);
                },
                error: ({
                    responseText
                }) => {
                    toastr.error(responseText);
                }
            })
        }

        function set_html_produk_bar(data) {
            let html = `<div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light active" onclick="change_display('bar')"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2" onclick="change_display('list')"><i class="fa fa-bars"></i></button>
                            </div>
                        </div>
                    </div>`;
            if (data.length === 0) {
                html += `<div class="col-12 text-center">
                    <p class="text-muted">Produk tidak ada</p>
                 </div>`;
            } else {
                data.forEach(produk => {
                    html += `
                <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="/storage/foto-produk/${produk.foto}" alt="${produk.nama}" style="max-height: 200px; object-fit: contain;">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square" href="/produk/detail/${produk.id}">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="#">${produk.nama}</a>
                            <div class="mt-2">
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <h6 class="mb-0 mr-2">Harga Ecer:</h6>
                                    <h6 class="mb-0">Rp${parseInt(produk.harga).toLocaleString('id-ID')}</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    <h6 class="mb-0 mr-2">Harga Grosir:</h6>
                                    <h6 class="mb-0">Rp${parseInt(produk.harga_grosir).toLocaleString('id-ID')}</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <h6 class="mb-0 mr-2">Harga Pengampu:</h6>
                                    <h6 class="mb-0">${produk.harga_pengampu}</h6>
                                </div>
                            </div>
                            <div class="mt-2 ${produk.stok != 0 ? 'text-success' : 'text-danger'} font-weight-bold">
                                ${produk.stok != 0 ? 'Stok tersedia (' + produk.stok + ')' : 'Stok habis'}
                            </div>
                            <div class="mt-3 text-muted small">
                                <p class="mb-0">Harga Ecer: untuk pembelian di bawah 10 kg</p>
                                <p class="mb-0">Harga Grosir: untuk pembelian 10–30 kg</p>
                                <p class="mb-0">Harga Pengampu: untuk pembelian di atas 30 kg</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                });
            }
            $('#data_produk_bar').html(html);
        }

        function set_html_produk_list(data) {
            let html = `<div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                           <div>
                                <button class="btn btn-sm btn-light" onclick="change_display('bar')"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2 active" onclick="change_display('list')"><i class="fa fa-bars"></i></button>
                            </div>
                        </div>
                    </div>`;
            if (data.length === 0) {
                html += `<div class="col-12 text-center">
                    <p class="text-muted">Produk tidak ada</p>
                 </div>`;
            } else {
                data.forEach(produk => {
                    html += `
                    <div class="col-12 pb-3">
                        <div class="product-item bg-light d-flex flex-wrap align-items-center p-3 mb-3">
                            <div class="product-img position-relative overflow-hidden mr-3" style="flex: 0 0 150px;">
                                <img class="img-fluid" src="/storage/foto-produk/${produk.foto}" alt="${produk.nama}" style="height: 150px; width: 150px; object-fit: contain;">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a class="h5 text-decoration-none text-dark" href="/produk/detail/${produk.id}">${produk.nama}</a>
                                        <div class="d-flex align-items-center mt-2">
                                            <h5 class="text-dark mb-0">Rp${parseInt(produk.harga).toLocaleString('id-ID')}</h5>
                                            ${produk.harga_diskon ? `<h6 class="text-muted ml-3 mb-0"><del>Rp${parseInt(produk.harga_diskon).toLocaleString('id-ID')}</del></h6>` : ''}
                                        </div>
                                        <div class="mt-2 ${produk.stok != 0 ? 'text-success' : 'text-danger'} font-weight-bold">
                                            ${produk.stok != 0 ? 'Stok tersedia (' + produk.stok + ')' : 'Stok habis'}
                                        </div>
                                    </div>
                                    <div class="product-action mt-2 mt-md-0">
                                        <a class="btn btn-outline-dark btn-square" href="/produk/detail/${produk.id}">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });
            }
            $('#data_produk_list').html(html);
        }


        function render_pagination_bar(currentPage, totalPages) {
            let paginationHtml = '<ul class="pagination justify-content-center">';

            if (currentPage > 1) {
                paginationHtml +=
                    `<li class="page-item"><a class="page-link" href="javascript:filter_data(${currentPage - 1})">Previous</a></li>`;
            }

            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="javascript:filter_data(${i})">${i}</a>
        </li>`;
            }

            if (currentPage < totalPages) {
                paginationHtml +=
                    `<li class="page-item"><a class="page-link" href="javascript:filter_data(${currentPage + 1})">Next</a></li>`;
            }

            paginationHtml += '</ul>';
            $('#pagination').html(paginationHtml);
        }

        function change_display(display) {
            if (display == 'bar') {
                $('#data_produk_bar').show();
                $('#data_produk_list').hide();
            } else {
                $('#data_produk_bar').hide();
                $('#data_produk_list').show();
            }
        }
    </script>
@endsection

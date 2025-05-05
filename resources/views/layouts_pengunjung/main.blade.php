<!DOCTYPE html>
<html lang="en">

@include('layouts_pengunjung.header')

<body>

    @include('layouts_pengunjung.navbar')

    @yield('content')

    @include('layouts_pengunjung.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i> <img
            src="{{ asset('assets/images/favicon.png') }}" alt="Kembali ke atas" style="width: 20px; height: 20px;"></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('pengunjung') }}/lib/easing/easing.min.js"></script>
    <script src="{{ asset('pengunjung') }}/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="{{ asset('pengunjung') }}/mail/jqBootstrapValidation.min.js"></script>
    <script src="{{ asset('pengunjung') }}/mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('pengunjung') }}/js/main.js"></script>
    <script>
        function rupiahFormat(angka) {
            angka = Number(angka);
            var options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            };
            return angka.toLocaleString('id-ID', options);
        }
    </script>
    @yield('script')
</body>

</html>

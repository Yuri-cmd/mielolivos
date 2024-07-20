<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Coorporacion marsam</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Men&uacute; <br> Bienvenido
                        {{ Session::get('usuario') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <hr>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('almacen') }}">Almacen</a>
                        </li>
                        <hr>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('logout') }}">Cerar
                                Sesi&oacute;n</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <script>
        let token = "{{ csrf_token() }}";
        let fecha = "{{ formatoFechaUno() }}";
        let urlGetProducto = "{{ route('getProducto') }}";
        let urlUpdateEstadoProducto = "{{ route('updateEstadoProducto') }}";
        let urlUpdateStockProducto = "{{ route('updateStockProducto') }}";
        let urlUpdateProducto = "{{ route('updateProducto') }}";
        let urlCreateProducto = "{{ route('createProducto') }}";
        let urlCreateAsociado = "{{ route('createAsociado') }}";
        let urlDeleteAsociado = "{{ route('deleteAsociado') }}";
        let urlCreateGrupo = "{{ route('createGrupo') }}";
        let urlDeleteGrupo = "{{ route('deleteGrupo') }}";
        let urlCreateCobrador = "{{ route('createCobrador') }}";
        let urlDeleteCobrador = "{{ route('deleteCobrador') }}";
        let urlCreateDetalleGrupo = "{{ route('createDetalleGrupo') }}";
        let urlSaveDetalle = "{{ route('saveDetalle') }}";
        let urlGetGrupoDetalle = "{{ route('getGrupoDetalle') }}";
        let urlGetMaster = "{{ route('getMaster') }}";
        let urlGetMasterUsuario = "{{ route('getMasterUsuario') }}";
        let urlMasterStock = "{{ route('masterStock') }}";
        let urlUpdateDepositoTaxiGrupo = "{{ route('updateDepositoTaxiGrupo') }}";
        let urlUpdateCampoUsuario = "{{ route('updateCampoUsuario') }}";
        let urlGetDetalleVendedor = "{{ route('getDetalleVendedor') }}";
        let urlGetVentas = "{{ route('getVentas') }}";
        let urlGetProductoMaster = "{{ route('getProductoMaster') }}";
        let urlBuscarUsuarios = "{{ route('buscar.usuarios') }}";
        let urlDetalleVenta = "{{ route('detalleVenta') }}";
        let urlSaveCajaChica = "{{ route('saveCajaChica') }}";
        let urlGetCajaChica = "{{ route('getCajaChica') }}";
        let urlUpdateSaldoCajaChica = "{{ route('updateSaldoCajaChica') }}";
    </script>
    <div style="display: flex; justify-content: space-around">
        <div>
            <a type="button" class="btn btn-primary">Primary</a>
        </div>
        <div>
            <a type="button" class="btn btn-secondary">Secondary</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Date Range Picker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>

</html>

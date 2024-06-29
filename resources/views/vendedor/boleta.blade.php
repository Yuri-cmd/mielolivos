<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- DataTables JS -->
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/usuario.css') }}">
</head>
<style>
    .sales-summary {}
</style>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Coorporacion marsam</a>
        </div>
    </nav>

    <div style="width: 100%; margin-top: 100px;display: flex;justify-content: center;">
        <div
            style="display: flex; flex-direction: column; align-items: center; border-radius: 20px; width: 398px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; padding: 20px;">
            <div class="sales-summary" style="width: 100%; padding: 10px">
                <div class="header-info mb-2" style="display: flex; justify-content: space-between;">
                    <span>Asesor: {{ $vendedor->usuario }}</span>
                    <span>{{ formatoFechaDos($venta->fecha) }}</span>
                </div>
                <div class="mb-4">
                    <div>
                        <h4 class="text-center" id="nombreCliente">{{ $venta->nombre }}</h4>
                    </div>
                    <div class="text-center">
                        <h5 id="direccionT">{{ $venta->jr }} / {{ $venta->urb }}</h5>
                    </div>
                    <div class="text-center">
                        <span style="font-weight: bold; text-decoration: underline;">Tocar:</span>
                        <span id="tocart">{{ $venta->tocar }}</span> <span
                            id="colorT">{{ $venta->color }}</span>
                    </div>
                </div>
                <div class="text-center">
                    <h4 style="font-weight: bold; text-decoration: underline;">
                        <a style="text-decoration: none; color: black"
                            id="telt">{{ $venta->telefono }}</a>
                    </h4>
                </div>
                <div style="display: flex; justify-content: center; width: 100%;">
                    <ul class="list-unstyled" style="margin: 0; width: 100%;">
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($detalle as $d)
                            @php
                                $total += floatval($d->cantidad) * floatval($d->precio);
                            @endphp
                            <li><span>{{ $d->cantidad }} {{ $d->nombre }}</span>
                                <span>S/{{ $d->cantidad * $d->precio }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <p class="text-end fw-bold" style="margin-top: -10px;" id="total">S/{{ $total }}</p>
                <input type="text" id="totalAmount" value="" hidden>
            </div>
            <div class="payment-details">
                <div class="row text-center">
                    <div class="col cuotas">
                        <span>ADELANTO</span>
                        <span>
                            {{ $formatoFecha }}
                        </span>
                    </div>
                    <div class="col cuotas">
                        <span>1RA CUOTA</span>
                        <span>
                            {{ $formatoFecha1 }}
                        </span>
                    </div>
                    <div class="col cuotas">
                        <span>2DA CUOTA</span>
                        <span>
                            {{ $formatoFecha2 }}
                        </span>
                    </div>
                    {{-- <div class="col">Pendiente</div> --}}
                </div>
                <div class="row text-center" style="margin-top: -10px;">
                    <div class="col"><input id="input1" type="number" class="form-control"
                            value="{{ $cuotas[0]->cuota1 }}" disabled></div>
                    <div class="col"><input id="input2" type="number" class="form-control"
                            value="{{ $cuotas[0]->cuota2 }}" disabled></div>
                    <div class="col"><input id="input3" type="number" class="form-control"
                            value="{{ $cuotas[0]->cuota3 }}" disabled></div>
                </div>
                <div class="firma mt-3" style="display: flex; flex-direction: column; align-items: center;">
                    <div style="display: flex; justify-content: center;">
                        <label for="firma">Firma:</label>
                        <img src='{{ asset("storage/signatures/{$venta->firma}") }}' alt="" width="300"
                            height="200">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let token = "{{ csrf_token() }}";
        let urlSaveVenta = "{{ route('saveVenta') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('js/vendedor.js') }}"></script>
</body>

</html>

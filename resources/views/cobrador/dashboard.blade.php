@extends('layout/cobrador-layaout')
@section('content')
    <div class="container">
        <div class="header">
            <div class="header-info">
                <h5>{{ $fecha }}</h5>
            </div>
            <div class="header-detalle">
                <h5>Ventas del D&iacute;a</h5>
                <h5 style="margin-left: 10px;">{{ date('d') }}</h5>
            </div>
        </div>
        <div class="mt-3 mb-3 content-buttons">
            <div>
                <button type="button" class="btn btn-light" id="filter-cuota1">1RA Cuota</button>
            </div>
            <div>
                <button type="button" class="btn btn-light" id="filter-cuota2">2DA Cuota</button>
            </div>
            <div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#estadisticas">Estadisticas</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">datos</th>
                        <th scope="col">Deuda</th>
                        <th scope="col">D&iacute;as Vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $i => $venta)
                        <tr onclick="mostrar({{ $venta->id }})" style="cursor: pointer;">
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>
                                <span>{{ $venta->nombre }}</span> <br>
                                <small>{{ $venta->usuario }}</small>
                            </td>
                            <td><span class="badge {{colorPago($venta->deuda - $venta->total_pagos)}}">{{ $venta->deuda - $venta->total_pagos }}</span></td>
                            <td><span
                                    class="badge {{ $venta->color_vencimiento }}">{{ $venta->dias_desde_venta }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div style="display: flex; justify-content: end;">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success">Siguiente</button>
        </div> --}}
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle de Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="number" id="idVenta" hidden>
                    <div class="sales-summary">
                        <div class="header-info mb-2" style="">
                            <span>Asesor: <a href="tel:+521234567890"
                                    style="font-weight: bold; text-decoration: underline; color: black;"
                                    id="asesor">Jorge
                                    Bernal</a></span>
                            <span id="fechaVenta"></span>
                        </div>
                        <div class="mb-4">
                            <div>
                                <h4 class="text-center" id="cliente"></h4>
                            </div>
                            <div class="text-center">
                                <h5><span id="jr"></span> / <span id="urb"></span></h5>
                            </div>
                            <div class="text-center">
                                <span style="font-weight: bold; text-decoration: underline;" id="piso"></span> /
                                <span style="font-weight: bold; text-decoration: underline;" id="pisos"></span> /
                                <span style="font-weight: bold; text-decoration: underline;" id="color"></span>
                            </div>
                            <div class="text-center">
                                <span style="font-weight: bold; text-decoration: underline;">Tocar:</span>
                                <span id="tocar">3RA PUERTA MARRÓN</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 style="font-weight: bold; text-decoration: underline;">
                                <a href="tel:+521234567890" style="text-decoration: none; color: black" id="telefono"></a>
                            </h4>
                        </div>
                        <ul class="list-unstyled" style="margin: 0;">

                        </ul>
                        <p class="text-end fw-bold" style="margin-top: -10px;" id="total"></p>
                    </div>
                    <div class="payment-details">
                        <div class="row text-center">
                            <div class="col cuotas">
                                <span>ADELANTO</span>
                                <span id="fecha1"></span>
                            </div>
                            <div class="col cuotas">
                                <span>1RA CUOTA</span>
                                <span id="fecha2"></span>
                            </div>
                            <div class="col cuotas">
                                <span>2DA CUOTA</span>
                                <span id="fecha3"></span>
                            </div>
                            {{-- <div class="col">Pendiente</div> --}}
                        </div>
                        <div class="row text-center" style="margin-top: -10px;">
                            <div class="col"><input type="number" class="form-control" id="cuota1" readonly></div>
                            <div class="col"><input type="number" class="form-control" id="cuota2" readonly></div>
                            <div class="col"><input type="number" class="form-control" id="cuota3" readonly></div>
                            {{-- <div class="col"><input type="number" class="form-control" value="180"></div> --}}
                        </div>
                        <div class="firma mt-3" style="display: flex; flex-direction: column; align-items: center;">
                            <div style="display: flex; justify-content: center;">
                                <label for="firma">Firma:</label>
                                <img id="firma" src='' alt="" width="300" height="200">
                            </div>
                        </div>
                        <input type="text" id="restante" value="" hidden>
                        <div class="mt-3" id="abonoform">
                            <label for="abono">Monto del abono</label>
                            <input type="number" class="form-control" id="abono" min="1" required>
                        </div>
                        <div class="table-responsive" style="margin-top:10px;">
                            <table class="table">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th></th>
                                        <th>Abono</th>
                                        <th>Pendiente</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-table-body">

                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center; display: none;" id="cancelado">
                            <h2 style="font-weight: bold; color: red;">CANCELADO</h2>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="submit">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="estadisticas" tabindex="-1" aria-labelledby="estadisticasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="estadisticasLabel">Estadisticas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="estadisticas">
                        <div class="content-estadistica">
                            <div>
                                <span>ACUMULADO</span>
                                <span class="badge text-bg-success">1000</span>
                            </div>
                            <div>
                                <span>COBRANZA</span>
                                <span class="badge text-bg-light">1000</span>
                            </div>
                        </div>
                        <div class="content-estadistica">
                            <div>
                                <span style="text-align: start">RECIBIDO</span>
                                <span class="badge text-bg-light">1000</span>
                            </div>
                            <div>
                                <span style="text-align: start">COMISIÓN</span>
                                <span class="badge text-bg-light">1000</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

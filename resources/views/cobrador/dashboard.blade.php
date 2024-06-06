@extends('layout/cobrador-layaout')
@section('content')
    <div class="container">
        <div class="header">
            <div class="header-info">
                <h5>{{ $fecha }}</h5>
            </div>
            <div class="header-detalle">
                <h5>Ventas del Día</h5>
                <h5 style="margin-left: 10px;">{{ date('d') }}</h5>
            </div>
        </div>
        <div class="mt-3 mb-3 content-buttons">
            <div>
                <button type="button" class="btn btn-light">1RA Cuota</button>
            </div>
            <div>
                <button type="button" class="btn btn-light">2DA Cuota</button>
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
                        <th scope="col">Días Vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                        <td><span class="badge text-bg-danger">500</span></td>
                    </tr>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                    </tr>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                    </tr>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">500</span>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                    </tr>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                    </tr>
                    <tr onclick="mostrar()" style="cursor: pointer;">
                        <th scope="row">1</th>
                        <td>
                            <span>Rosa Maria Lima Guerrero</span> <br>
                            <small>Asesor: Carlos</small>
                        </td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                        <td><span class="badge text-bg-secondary">500</span></td>
                    </tr>
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
                    <div class="sales-summary">
                        <div class="header-info mb-2" style="">
                            <span>Asesor: <a href="tel:+521234567890"
                                    style="font-weight: bold; text-decoration: underline; color: black;">Jorge
                                    Bernal</a></span>
                            <span>{{ $fecha }}</span>
                        </div>
                        <div class="mb-4">
                            <div>
                                <h4 class="text-center">LUIS ALBERTO SOTIL GUERRERO</h4>
                            </div>
                            <div class="text-center">
                                <h5>JR Tritoma 296 / Urb. El Rosario</h5>
                            </div>
                            <div class="text-center">
                                <span style="font-weight: bold; text-decoration: underline;">Piso:</span>
                                <span>1</span>
                                <span style="font-weight: bold; text-decoration: underline;">/ N° Pisos:</span>
                                <span>3</span>
                                <span style="font-weight: bold; text-decoration: underline;">/ Color:</span>
                                <span>Verde</span>
                            </div>
                            <div class="text-center">
                                <span style="font-weight: bold; text-decoration: underline;">Tocar:</span>
                                <span>3RA PUERTA MARRÓN</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 style="font-weight: bold; text-decoration: underline;">
                                <a href="tel:+521234567890" style="text-decoration: none; color: black">927300123</a>
                            </h4>
                        </div>
                        <ul class="list-unstyled" style="margin: 0;">
                            <li><span>2 MIEL</span> <span>S/50.00</span></li>
                            <li><span>1 COLAGENO</span><span>S/25.00</span></li>
                            <li><span>4 MACA NEGRA</span><span>S/100.00</span></li>
                            <li><span>5 PROPOLEO</span><span>S/125.00</span></li>
                        </ul>
                        <p class="text-end fw-bold" style="margin-top: -10px;">S/300.00</p>
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
                            <div class="col"><input type="number" class="form-control" value="120"></div>
                            <div class="col"><input type="number" class="form-control" value="100"></div>
                            <div class="col"><input type="number" class="form-control" value="80"></div>
                            {{-- <div class="col"><input type="number" class="form-control" value="180"></div> --}}
                        </div>
                        <div class="firma mt-3" style="display: flex; flex-direction: column; align-items: center;">
                            <div style="display: flex; justify-content: center;">
                                <label for="firma">Firma:</label>
                                <canvas id="signature-pad" class="signature-pad" width="400" height="200"></canvas>
                            </div>
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
                                <tbody>
                                    <tr>
                                        <td>Sabado 25 Mayo 2024</td>
                                        <td><span class="precios-cobrador">25</span></td>
                                        <td><span class="precios-cobrador">100</span></td>
                                    </tr>
                                    <tr>
                                        <td>Lunes 27 Mayo 2024</td>
                                        <td><span class="precios-cobrador">10</span></td>
                                        <td><span class="precios-cobrador">90</span></td>
                                    </tr>
                                    <tr>
                                        <td>Lunes 04 Junio 2024</td>
                                        <td><span class="precios-cobrador">20</span></td>
                                        <td><span class="precios-cobrador">70</span></td>
                                    </tr>
                                    <tr>
                                        <td>Sabado 09 Junio 2024</td>
                                        <td><span class="precios-cobrador">70</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center;">
                            <h2 style="font-weight: bold; color: red;">CANCELADO</h2>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atrás</button>
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

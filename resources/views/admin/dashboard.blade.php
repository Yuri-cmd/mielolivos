@extends('layout/admin-layout')
@section('content')
    <div class="contenedor">
        <div class="contenedor-buttons">
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="buscador">BUSCADOR
                    G.</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="oficina">VENTAS DE
                    OFICINA</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow"
                    id="comision">COMISIONES</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow"
                    id="descuento">DESCUENTOS</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow"
                    id="estadistica">ESTADISTICAS</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="cuadreGeneral">CUADRE
                    GENERAL</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="cajaChica">CAJA
                    CHICA</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="master">MASTER</button>
            </div>
        </div>
        <div class="contenedor-dashboard">
            <div class="contenedor-usuarios">
                <div class="seccion-uno">
                    <div class="contenedor-listado">
                        <div>
                            <h5 class="fw-bold">ASOCIADOS</h5>
                        </div>
                        <div class="contenedor-agregar">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#agregarAsociado">
                                <i class="bi bi-plus-square-dotted"></i>
                            </button>
                        </div>
                        <div class="usuarios">
                            @foreach ($asociados as $asociado)
                                <div class="cardm cardscroll" data-id="{{ $asociado->id }}">
                                    <span>{{ strtoupper($asociado->usuario) }}<a data-id="{{ $asociado->id }}"
                                            class="eliminarA deleteAsociado">x</a></span>
                                    <small>{{ $fecha }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="contenedor-listado">
                        <div>
                            <h5 class="fw-bold">GRUPOS</h5>
                        </div>
                        <div class="contenedor-agregar">
                            <input type="date" id="filterDate" class="form-control">
                        </div>
                        <div class="usuarios" id="contenedorGrupos">
                            @foreach ($grupos as $grupo)
                                <div class="cardm grupo" onclick="visualizarGrupo({{ $grupo->id }})"
                                    data-id="{{ $grupo->id }}" style="cursor: pointer">
                                    <span>{{ $grupo->nombre }}</span>
                                    <small>{{ formatoFechaDos($grupo->fecha) }}</small>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="contenedor-listado-bottom">
                    <div>
                        <h5 class="fw-bold">COBRADORES</h5>
                    </div>
                    <div class="contenedor-agregar">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#agregarCobrador">
                            <i class="bi bi-plus-square-dotted"></i>
                        </button>
                    </div>
                    <div class="usuarios">
                        @foreach ($cobradores as $cobrador)
                            <div class="cardm cobrador" data-id="{{ $cobrador->id }}">
                                <span>{{ strtoupper($cobrador->usuario) }}<a data-id="{{ $cobrador->id }}"
                                        class="eliminarA deleteCobrador">x</a></span>
                                <small>{{ $fecha }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="contenedor-grafica">
                <div class="grafica">
                    <div>
                        <h5 style="font-weight: bold;">VENTAS DE LA SEMANA</h5>
                    </div>
                    <div>
                        <canvas id="barHorizontal"></canvas>
                    </div>
                </div>
                <div class="grupo" style="display: none; padding: 10px;">
                    <div style="width: width: 100%;">
                        <div style="display: flex; justify-content: space-between;">
                            <input type="text" value="" id="idGrupo" hidden>
                            <div class="cardm" style="margin-bottom: 10px; width: fit-content !important;">
                                <span class="spanGrupo"></span>
                                <small>{{ $fecha }}</small>
                            </div>
                            <div style="display: flex; width: 200px; height: 40px; align-items: center;"
                                id="contenedorCobradores">
                                <div style="width: fit-content">
                                    <span>Cobrador:</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cardH" id="cardho">
                        <div class="contenedor-row" id="contenedorVendedores">
                            <div>
                                <span>Asociados:</span>
                            </div>
                        </div>
                        <div class="contenedor-row">
                            <div>
                                <span>P.CAMPO:</span>
                            </div>
                            <div id="pcampo" style="display: flex; align-items: center;flex-direction: column;">

                            </div>
                            {{-- <div class="cardm" id="totalCampo">
                                <input type="number" class="form-control" inputmode="numeric">
                            </div> --}}
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" id="submitGrupo">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle Grupo --}}
    @include('admin.detalle-grupo')

    {{-- Creacion de usuarios --}}
    @include('admin.creacion-usuarios')

    <div class="modal fade" id="masterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Master</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="stockMasivo" class="form-label">Asignar Cantidad Masiva</label>
                        <input type="number" class="form-control" id="stockMasivo">
                    </div>
                    <table class="table" id="tableUsuarios" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardarBtn">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Buscador general --}}
    @include('admin.buscador')

    {{-- Cuadre general --}}
    <div class="modal fade" id="cuadreGeneralModal" tabindex="-1" aria-labelledby="cuadreGeneralModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="cuadreGeneralModalLabel"></h1> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h3 class="fw-bold">CUADRE GENERAL</h3>
                    </div>
                    <div class="text-center">
                        <h5 class="fw-bold">CUADRE PALACIO</h5>
                    </div>
                    <div class="text-center">
                        <span class="fw-bold">{{ getWeekInterval() }}</span>
                    </div>
                    <div>
                        <div class="text-center">
                            <h3 class="fw-bold">INGRESOS</h3>
                        </div>
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Efectivo</th>
                                        <th scope="col">Deposito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">VENTAS DE OFICINA</th>
                                        <td>1250</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">COBRANZA SEMANAL</th>
                                        <td>4000</td>
                                        <td>2500</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">CAJA VENDEDORES</th>
                                        <td>1200</td>
                                        <td>500</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">TOTAL</th>
                                        <td>6450</td>
                                        <td>3000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <div class="text-center">
                            <h3 class="fw-bold">GASTOS</h3>
                        </div>
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Efectivo</th>
                                        <th scope="col">Deposito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">CAJA CHICA</th>
                                        <td>180</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">SUELDO CHINO</th>
                                        <td>0</td>
                                        <td>1000</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">SUELDO RONNY</th>
                                        <td>1000</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">SUELDO GABRIEL</th>
                                        <td>500</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">PAGO VENDEDORES</th>
                                        <td>342</td>
                                        <td>900</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">PAGO COBRADORES</th>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadisticas --}}
    <div class="modal fade" id="estadisticasModal" tabindex="-1" aria-labelledby="estadisticasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="estadisticasModalLabel">ESTADISTICAS</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="">
                                <input type="text" class="form-control" id="buscarUsuario"
                                    placeholder="Buscar usuario" required>
                                <ul class="list-group mt-2" id="resultList">
                                    <!-- Resultados serán agregados dinámicamente -->
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fechaRango" id="fechaRango"
                                    placeholder="Seleccione un rango de fechas">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnFiltrar">Filtrar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="pieChart1"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="pieChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Caja chica --}}
    @include('admin.cajaChica')
    {{-- DESCUENTOS --}}
    <div class="modal fade" id="descuentoModal" tabindex="-1" aria-labelledby="descuentoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="descuentoModalLabel">DESCUENTOS</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">ASESOR</th>
                                <th scope="col">ENVASES</th>
                                <th scope="col">PAÑOS</th>
                                <th scope="col">PENDIENTE PROD.</th>
                                <th scope="col">TARDANZA</th>
                                <th scope="col">TOTAL</th>
                                <th scope="col">PARCHES</th>
                            </tr>
                        </thead>
                        <tbody id="tdescuento">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- COMISIONES --}}
    <div class="modal fade" id="comisionModal" tabindex="-1" aria-labelledby="comisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="comisionModalLabel">COMISIONES</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">ASESOR</th>
                                <th scope="col">PRODUCTOS</th>
                                <th scope="col">VENDEDOR</th>
                                <th scope="col">LIDER</th>
                                <th scope="col">COMISIÓN</th>
                                <th scope="col">DESCUENTOS</th>
                                <th scope="col">BONO</th>
                                <th scope="col">P.EFECTIVO</th>
                                <th scope="col">P.DEPOSITO</th>
                                <th scope="col">PAGO FINAL</th>
                            </tr>
                        </thead>
                        <tbody id="tcomision">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Venta oficina --}}
    @include('admin.ventas-oficina')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.0/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        const ctxHorizontal = document.getElementById('barHorizontal').getContext('2d');
        const barHorizontal = new Chart(ctxHorizontal, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: '',
                    data: [],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }],
            },
            options: {
                indexAxis: 'y', // <-- here
                responsive: true
            }
        });
    </script>
@endsection

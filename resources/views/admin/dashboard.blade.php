@extends('layout/admin-layout')
@section('content')
    <div class="contenedor">
        <div class="contenedor-buttons">
            <div>
                <button type="button" class="btn btn-light shadow">BUSCADOR G.</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">VENTAS DE OFICINA</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">COMISIONES</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">DESCUENTOS</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">ESTADISTICAS</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">CUADRE GENERAL</button>
            </div>
            <div>
                <button type="button" class="btn btn-light shadow">CAJA CHICA</button>
            </div>
        </div>
        <div class="contenedor-dashboard">
            <div class="contenedor-usuarios">
                <div class="seccion-uno">
                    <div class="contenedor-listado">
                        <div>
                            <h5>ASOCIADOS</h5>
                        </div>
                        <div class="contenedor-agregar">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#agregarAsociado">
                                <i class="bi bi-plus-square-dotted"></i>
                            </button>
                        </div>
                        <div class="usuarios">
                            @foreach ($asociados as $asociado)
                                <div class="cardm">
                                    <span>{{ strtoupper($asociado->usuario) }}<a data-id="{{ $asociado->id }}"
                                            class="eliminarA deleteAsociado">x</a></span>
                                    <small>{{ $fecha }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="contenedor-listado">
                        <div>
                            <h5>GRUPOS</h5>
                        </div>
                        <div class="contenedor-agregar">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#agregarGrupo">
                                <i class="bi bi-plus-square-dotted"></i>
                            </button>
                        </div>
                        <div class="usuarios">
                            @foreach ($grupos as $grupo)
                                <div class="cardm visualizar" data-id="{{ $grupo->id }}" style="cursor: pointer">
                                    <span>{{ $grupo->nombre }}</span>
                                    <small>{{ formatoFechaDos($grupo->fecha) }}</small>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="contenedor-listado-bottom">
                    <div>
                        <h5>COBRADORES</h5>
                    </div>
                    <div class="contenedor-agregar">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#agregarCobrador">
                            <i class="bi bi-plus-square-dotted"></i>
                        </button>
                    </div>
                    <div class="usuarios">
                        @foreach ($cobradores as $cobrador)
                            <div class="cardm">
                                <span>{{ strtoupper($asociado->usuario) }}<a data-id="{{ $cobrador->id }}"
                                        class="eliminarA deleteCobrador">x</a></span>
                                <small>{{ $fecha }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="contenedor-grafica">
                <div>
                    <h5>VENTAS DE LA SEMANA</h5>
                </div>
                <div>
                    <canvas id="barHorizontal"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="display: flex; justify-content: space-between; width: 100%;">
                        <h1 class="modal-title fs-5 nameGrupo"></h1>
                        <h1 class="modal-title fs-5 fechaGrupo"></h1>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="display: flex; flex-direction: column;">
                    <div style="display: flex;">
                        <div style="width: 100%; height: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center">Vendedor</th>
                                        <th scope="col" style="text-align: center">Campo</th>
                                        <th scope="col" style="text-align: center">Vendidos</th>
                                        <th scope="col" style="text-align: center">Sobrantes</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleBody">

                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td style="text-align: center" class="table-light" id="totalCampo">10</td>
                                    <td style="text-align: center" class="table-light" id="totalVendido">10</td>
                                    <td style="text-align: center" class="table-light" id="totalSobrante">10</td>
                                </tfoot>
                            </table>
                        </div>
                        <div style="width: 100%; height: 300px;">
                            <canvas id="bar" style="width: 100%;"></canvas>
                        </div>
                    </div>
                    <div style="display: flex;width: 50%;justify-content: space-between;">
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="text-align: start" scope="col">Deposito (S/)</th>
                                        <th style="text-align: start; font-weight: unset; border: 1px solid gray;" id="depositod" class="table-warning"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: start" scope="row">Taxi(S/)</th>
                                        <td style="text-align: start; border: 1px solid gray;" class="table-warning" id="taxid"></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: start" scope="row">Efectivo(S/)</th>
                                        <td style="text-align: start; border: 1px solid gray;" id="efectivod"></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: start" scope="row">Por cobrar(S/)</th>
                                        <td style="text-align: start;border: 1px solid gray;">25</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="text-align: start;" scope="col">Creditos (U)</th>
                                        <th style="text-align: start; font-weight: unset;border: 1px solid gray;">150</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: start" scope="row">Contados(U)</th>
                                        <td style="text-align: start;border: 1px solid gray;">25</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="agregarAsociado" tabindex="-1" aria-labelledby="agregarAsociadoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregarAsociadoLabel">Agregar Asociado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAsociado">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="password" class="form-control" id="clave" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="submitAsociado">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="agregarGrupo" tabindex="-1" aria-labelledby="agregarGrupoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregarGrupoLabel">Agregar Grupo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGrupo">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Grupo</label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="submitGrupo">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="agregarCobrador" tabindex="-1" aria-labelledby="agregarCobradorLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregarCobradorLabel">Agregar Cobrador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCobrador">
                        <div class="mb-3">
                            <label for="usuarioc" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuarioc" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="password" class="form-control" id="clavec" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="submitCobrador">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="grupoDetalle" aria-hidden="true" aria-labelledby="grupoDetalleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 tituloGrupo" id="grupoDetalleLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="width: width: 100%;">
                        <div style="display: flex; justify-content: space-between;">
                            <input type="text" value="" id="idGrupo" hidden>
                            <div class="cardm" style="margin-bottom: 10px;">
                                <span class="spanGrupo"></span>
                                <small>{{ $fecha }}</small>
                            </div>
                            <div style="display: flex; width: 200px; height: 40px; align-items: center;">
                                <label for="usuarios">Asociados</label>&nbsp;
                                <select class="form-control" id="usuarios">
                                    <option value="0">--Elegir--</option>
                                    @foreach ($asociados as $asociado)
                                        <option value="{{ $asociado->id }}/{{ strtoupper($asociado->usuario) }}">
                                            {{ strtoupper($asociado->usuario) }}</option>
                            </div>
                            @endforeach
                            </select>
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
                            <div id="pcampo">

                            </div>
                            <div class="cardm" id="totalCampo">
                                <input type="number" class="form-control" inputmode="numeric">
                            </div>
                        </div>
                        <div class="contenedor-row">
                            <div>
                                <span>P.VENDIDOS:</span>
                            </div>
                            <div id="pvendido">

                            </div>
                            <div class="cardm" id="totalVendido">
                                <input type="number" class="form-control" inputmode="numeric">
                            </div>
                        </div>
                        <div class="contenedor-row">
                            <div>
                                <span>P.SOBRANTES:</span>
                            </div>
                            <div id="psobrantes">

                            </div>
                            <div class="cardm" id="totalSobrante">
                                <input type="number" class="form-control" inputmode="numeric">
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                                <div style="margin-right: 10px;">
                                    <span>DEPO:</span>
                                </div>
                                <div class="cardm">
                                    <input type="text" class="form-control" style="width: 50px;" id="depo">
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                                <div style="margin-right: 10px;">
                                    <span>TAXI:</span>
                                </div>
                                <div class="cardm">
                                    <input type="text" class="form-control" style="width: 50px;" id="taxi">
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                                <div style="margin-right: 10px;">
                                    <span>EFECTIVO:</span>
                                </div>
                                <div class="cardm">
                                    <input type="text" class="form-control" style="width: 50px;" id="efectivo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="guardarDetalle">Guardar</button>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.0/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
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

@extends('layout/admin-layout')
@section('content')
    <div class="contenedor">
        <div class="contenedor-buttons">
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="buscador">BUSCADOR G.</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow">VENTAS DE OFICINA</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="comision">COMISIONES</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="descuento">DESCUENTOS</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="estadistica">ESTADISTICAS</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="cuadreGeneral">CUADRE GENERAL</button>
            </div>
            <div>
                <button type="button"style="font-weight: bold;" class="btn btn-light shadow" id="cajaChica">CAJA CHICA</button>
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
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#agregarGrupo">
                                <i class="bi bi-plus-square-dotted"></i>
                            </button>
                        </div>
                        <div class="usuarios" id="contenedorGrupos">
                            @foreach ($grupos as $grupo)
                                <div class="cardm" onclick="visualizarGrupo({{ $grupo->id }})"
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
                            <div class="cardm cardscroll" data-id="{{ $cobrador->id }}">
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

    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input type="text" id="idGrupoView" value="" hidden>
                    <div style="display: flex;">
                        <div style="width: 100%; height: auto;">
                            <table class="table" id="miTabla">
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
                                        <th style="text-align: start; font-weight: unset; border: 1px solid gray;"
                                            id="depositod" class="table-warning" contenteditable="true">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: start" scope="row">Taxi(S/)</th>
                                        <td style="text-align: start; border: 1px solid gray;" id="taxid"
                                            class="table-warning" contenteditable="true">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: start" scope="row">Efectivo(S/)</th>
                                        <td style="text-align: start; border: 1px solid gray;" id="efectivod"></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: start" scope="row">Por cobrar(S/)</th>
                                        <td style="text-align: start;border: 1px solid gray;" id="porcobrar"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="text-align: start;" scope="col">Creditos (U)</th>
                                        <th style="text-align: start; font-weight: unset;border: 1px solid gray;"
                                            id="creditos"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: start" scope="row">Contados(U)</th>
                                        <td style="text-align: start;border: 1px solid gray;" id="contado"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" id="deleteGrupo">Eliminar</a>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
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
    <div class="modal fade" id="agregarGrupo" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="agregarGrupoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregarGrupoLabel">Agregar Grupo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Grupo</label>
                        <input type="text" class="form-control" id="nombre" required>
                    </div>
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

    <div class="modal fade" id="detalleVendedorModal" tabindex="-1" aria-labelledby="detalleVendedorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detalleVendedorModalLabel">Carpeta de Asesor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="idGrupoUsuario" value="" hidden>
                    <div class="text-center">
                        <h3 id="nombreVendedor"></h3>
                        <h6 id="fechaVenta"></h6>
                    </div>
                    <div>
                        <table class="table" id="tableDetalle">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Vendidos</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <td></td>
                                <td></td>
                                <td class="text-center" id="totalVendedor"></td>
                            </tfoot>
                        </table>
                    </div>
                    <div>
                        <table class="table" id="tableDetalleVenta">
                            <thead>
                                <tr>
                                    <th scope="col">Creditos</th>
                                    <th scope="col">Contados</th>
                                    <th scope="col">Adelantos</th>
                                    <th scope="col">Por cobrar</th>
                                    <th scope="col">Caja del dia</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrarDetalle">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Buscador general --}}
    <div class="modal fade" id="buscadorModal" tabindex="-1" aria-labelledby="buscadorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="buscadorModalLabel">Buscador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table" id="tableBuscador" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Asesor</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="buscadorDetalle" tabindex="-1" aria-labelledby="buscadorDetalleLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buscadorDetalleLabel">Detalle de Venta</h5>
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
                                <a href="tel:+521234567890" style="text-decoration: none; color: black"
                                    id="telefono"></a>
                            </h4>
                        </div>
                        <ul class="list-unstyled" style="margin: 0;">

                        </ul>
                        <p class="text-end fw-bold" style="margin-top: -10px;" id="total"></p>
                    </div>
                    <div class="payment-details">
                        <div class="row text-center">
                            <div class="col cuotas">
                                <span>ADELANTO</span><br>
                                <span id="fecha1"></span>
                            </div>
                            <div class="col cuotas">
                                <span>1RA CUOTA</span><br>
                                <span id="fecha2"></span>
                            </div>
                            <div class="col cuotas">
                                <span>2DA CUOTA</span><br>
                                <span id="fecha3"></span>
                            </div>
                            {{-- <div class="col">Pendiente</div> --}}
                        </div>
                        <div class="row text-center" style="">
                            <div class="col"><input type="number" class="form-control" id="cuota1" readonly>
                            </div>
                            <div class="col"><input type="number" class="form-control" id="cuota2" readonly>
                            </div>
                            <div class="col"><input type="number" class="form-control" id="cuota3" readonly>
                            </div>
                            {{-- <div class="col"><input type="number" class="form-control" value="180"></div> --}}
                        </div>
                        <div class="firma mt-3" style="display: flex; flex-direction: column; align-items: center;">
                            <div style="display: flex; justify-content: center;">
                                <label for="firma">Firma:</label>
                                <img id="firma" src='' alt="" width="300" height="200">
                            </div>
                        </div>
                        <input type="text" id="restante" value="" hidden>
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
    <div class="modal fade" id="cajaChicaModal" tabindex="-1" aria-labelledby="cajaChicaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="cajaChicaModalLabel">CAJA CHICA</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="display: flex; justify-content: end">
                        <button type="button" class="btn btn-primary mb-2" id="agregarCaja">Agregar</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Gasto</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody id="cajadetalle">

                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: space-between">
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">SALDO</th>
                                        <th scope="col" id="saldoCaja" class="table-warning" contenteditable="true">S/0.00</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">TOTAL</th>
                                        <th scope="col" id="montoCaja"></th>
                                    </tr>
                                </thead>
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
    <div class="modal fade" id="cajaInsert" tabindex="-1" aria-labelledby="cajaInserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="cajaInserLabel">CAJA CHICA</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="nombreGasto" class="form-label">Nombre del gasto</label>
                            <input type="text" class="form-control" id="nombreGasto" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="monto" class="form-label">monto</label>
                            <input type="number" class="form-control" id="cajamonto">
                        </div>
                        <div id="emailHelp" class="form-text" style="display:none;">Todos los campos son requeridos</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="guardarCaja">Guardar</button>
                </div>
            </div>
        </div>
    </div>
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
                        <tbody>
                            <tr>
                                <th scope="row">Sara</th>
                                <td>2</td>
                                <td>1</td>
                                <td>S/65</td>
                                <td>3</td>
                                <td>S/92</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <th scope="row">Gabriel</th>
                                <td>1</td>
                                <td>0</td>
                                <td>S/130</td>
                                <td>1</td>
                                <td>S/139</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">TOTAL</th>
                                <td>4</td>
                                <td>1</td>
                                <td>S/195</td>
                                <td>4</td>
                                <td>S/231</td>
                                <td>3</td>
                            </tr>
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
                        <tbody>
                            <tr>
                                <th scope="row">Sara</th>
                                <td>90</td>
                                <td>35</td>
                                <td>55</td>
                                <td>S/665</td>
                                <td>S/92</td>
                                <td>-</td>
                                <td>S/173</td>
                                <td>S/400</td>
                                <td>S/573</td>
                            </tr>
                            <tr>
                                <th scope="row">Gabriel</th>
                                <td>101</td>
                                <td>50</td>
                                <td>51</td>
                                <td>s/758</td>
                                <td>S/139</td>
                                <td>50</td>
                                <td>S/169</td>
                                <td>S/500</td>
                                <td>S/669</td>
                            </tr>
                            <tr>
                                <th scope="row">TOTAL</th>
                                <td>191</td>
                                <td>105</td>
                                <td>86</td>
                                <td>S/1423</td>
                                <td>S/231</td>
                                <td>50</td>
                                <td>S/342</td>
                                <td>S/900</td>
                                <td>S/1242</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
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

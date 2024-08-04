<div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
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

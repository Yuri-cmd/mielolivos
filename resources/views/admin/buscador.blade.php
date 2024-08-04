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

<div class="modal fade" id="buscadorDetalle" tabindex="-1" aria-labelledby="buscadorDetalleLabel" aria-hidden="true">
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

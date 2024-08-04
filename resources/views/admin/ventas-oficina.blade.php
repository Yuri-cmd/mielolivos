<style>
    .sales,
    .customer-info {
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .cell {
        flex: 1;
        text-align: center;
    }

    input[type="number"],
    input[type="text"] {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        box-sizing: border-box;
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

    input[type="number"] {
        text-align: center;
    }
</style>
<div class="modal fade" id="oficinaModal" tabindex="-1" aria-labelledby="oficinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="oficinaModalLabel">VENTAS EN OFICINA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="sales">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">CLIENTE</label>
                        <input type="text" class="form-control" id="clienteO">
                    </div>
                    <div class="row" id="products1"></div>
                    <div class="row" id="products1Detail"></div>
                    <div class="row" id="products2"></div>
                    <div class="row" id="products2Detail"></div>
                    <div class="mb-3">
                        <label for="abono" class="form-label fw-bold">ABONO</label>
                        <input type="text" class="" id="abonoO" style="width: 80px">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" id="enviarVentaOficina" class="btn btn-success">Enviar</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="bOficina" style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">CLIENTE</th>
                                <th scope="col">ABONO</th>
                                <th scope="col">PENDIENTE</th>
                                <th scope="col">PRODUCTOS</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="oficinaDetalle" tabindex="-1" aria-labelledby="oficinaDetalleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="oficinaDetalleLabel">Detalle de Venta Oficina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="number" id="idVentaO" hidden>
                <div class="sales-summary">
                    <div class="mb-4">
                        <div>
                            <h4 class="text-center" id="nombreCliente"></h4>
                            <h6 class="text-center" id="fechaOficina"></h6>
                        </div>
                    </div>
                    <ul class="list-unstyled" style="margin: 0;">

                    </ul>
                    <p class="text-end fw-bold" style="" id="totalO"></p>
                </div>
                <div class="payment-details">
                    <input type="text" id="restanteO" value="" hidden>
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
                            <tbody id="payment-table-bodyO">

                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center; display: none;" id="canceladoO">
                        <h2 style="font-weight: bold; color: red;">CANCELADO</h2>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="submitO">Guardar</button>
            </div>
        </div>
    </div>
</div>

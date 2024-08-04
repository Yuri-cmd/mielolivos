<div class="modal fade" id="cajaChicaModal" tabindex="-1" aria-labelledby="cajaChicaModalLabel" aria-hidden="true">
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
                                    <th scope="col" id="saldoCaja" class="table-warning" contenteditable="true">
                                        S/0.00</th>
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

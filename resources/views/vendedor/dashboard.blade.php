@extends('layout/vendedor-layout')
@section('content')
    <div class="container">
        <div class="header">
            <div class="header-info">
                <span>Asesor: Jorge Bernal</span>
                <span>{{ $fecha }}</span>
            </div>
            <div class="header-detalle">
                <h5>Ventas del Día</h5>
                <h5 style="margin-left: 10px;">{{ date('d') }}</h5>
            </div>
        </div>
        <hr>
        <div class="sales">
            <div class="row">
                <div class="cell">Miel</div>
                <div class="cell">Alg</div>
                <div class="cell">Col</div>
                <div class="cell">Cart</div>
                <div class="cell">Gr</div>
            </div>
            <div class="row">
                <div class="cell"><input type="number" value="2" /></div>
                <div class="cell"><input type="number" value="0" /></div>
                <div class="cell"><input type="number" value="1" /></div>
                <div class="cell"><input type="number" value="0" /></div>
                <div class="cell"><input type="number" value="0" /></div>
            </div>
            <div class="row">
                <div class="cell">Mx</div>
                <div class="cell">Mc</div>
                <div class="cell">Mor</div>
                <div class="cell">Pro</div>
                <div class="cell">Po</div>
            </div>
            <div class="row">
                <div class="cell"><input type="number" value="0" /></div>
                <div class="cell"><input type="number" value="4" /></div>
                <div class="cell"><input type="number" value="0" /></div>
                <div class="cell"><input type="number" value="5" /></div>
                <div class="cell"><input type="number" value="0" /></div>
            </div>
            <div class="contado-checkbox">
                <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning">Contado</button>
            </div>
        </div>
        <hr>
        <div class="customer-info">
            <div class="col-sm">
                <label for="Nombre">Nombres y Apellidos</label>
                <input type="text" class="form-control" value="Luis Joyanes Aguilar Arevalo" />
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="MZ">MZ</label>
                    <input type="text" class="form-control" placeholder="Mz" aria-label="Mz">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="Lt">Lt</label>
                    <input type="text" class="form-control" placeholder="Lt" aria-label="Lt">
                </div>
                <div class="col-sm-7" style="text-align: center;">
                    <label for="Jr./Calle">Jr./Calle</label>
                    <input type="text" class="form-control" placeholder="Jr./Calle" aria-label="Jr./Calle">
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="Piso">Piso</label>
                    <input type="text" class="form-control" placeholder="Piso" aria-label="Piso">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="N° Pisos">N° Pisos</label>
                    <input type="text" class="form-control" placeholder="N° Pisos" aria-label="N° Pisos">
                </div>
                <div class="col-sm-7" style="text-align: center;">
                    <label for="Urbanización">Urbanización</label>
                    <input type="text" class="form-control" placeholder="Urbanización" aria-label="Urbanización">
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="Color">Color</label>
                    <input type="text" class="form-control" placeholder="Color" aria-label="Color">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="Tocar">Tocar</label>
                    <input type="text" class="form-control" placeholder="Tocar" aria-label="Tocar">
                </div>
            </div>
            <div class="col-sm">
                <label for="Teléfono">Teléfono</label>
                <input type="text" class="form-control" placeholder="Teléfono" aria-label="Teléfono">
            </div>
        </div>
        <div style="display: flex; justify-content: end;">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success">Siguiente</button>
        </div>
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
                            <span>Asesor: Jorge Bernal</span>
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
                            <button class="btn btn-secondary mt-2" id="clear">Borrar</button>
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
@endsection

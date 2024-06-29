@extends('layout/vendedor-layout')
@section('content')
    <div class="container">
        <div class="header">
            <div class="header-info">
                <span>Asesor: {{ ucwords(Session::get('usuario')) }}</span>
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
                @foreach ($productos1 as $p)
                    <div class="cell">{{ $p->abreviatura }}</div>
                @endforeach
            </div>
            <div class="row">
                @foreach ($productos1 as $p)
                    <div class="cell"><input class="valor" data-id="{{ $p->id }}"
                            data-nombre="{{ ucwords($p->nombre) }}" data-precio="{{ $p->precio1 }}" type="number"
                            value="0" /></div>
                @endforeach
            </div>
            <div class="row">
                @foreach ($productos2 as $p)
                    <div class="cell">{{ $p->abreviatura }}</div>
                @endforeach
            </div>
            <div class="row">
                @foreach ($productos2 as $p)
                    <div class="cell"><input class="valor" data-id="{{ $p->id }}"
                            data-nombre="{{ ucwords($p->nombre) }}" data-precio="{{ $p->precio1 }}" type="number"
                            value="0" /></div>
                @endforeach
            </div>
            <div class="contado-checkbox">
                <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning">Contado</button>
            </div>
        </div>
        <hr>
        <div class="customer-info">
            <div class="col-sm">
                <label for="Nombre">Nombres y Apellidos</label>
                <input type="text" class="form-control" id="nombre" value="" required />
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="MZ">MZ</label>
                    <input type="text" class="form-control" id="mz" placeholder="Mz" aria-label="Mz">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="Lt">Lt</label>
                    <input type="text" class="form-control" id="lt" placeholder="Lt" aria-label="Lt">
                </div>
                <div class="col-sm-7" style="text-align: center;">
                    <label for="Jr./Calle">Jr./Calle</label>
                    <input type="text" class="form-control" id="jr" placeholder="Jr./Calle"
                        aria-label="Jr./Calle">
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="Piso">Piso</label>
                    <input type="text" class="form-control" id="piso" placeholder="Piso" aria-label="Piso">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="N° Pisos">N° Pisos</label>
                    <input type="text" class="form-control" id="pisos" placeholder="N° Pisos" aria-label="N° Pisos">
                </div>
                <div class="col-sm-7" style="text-align: center;">
                    <label for="Urbanización">Urbanización</label>
                    <input type="text" class="form-control" id="urb" placeholder="Urbanización"
                        aria-label="Urbanización">
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm" style="text-align: center;">
                    <label for="Color">Color</label>
                    <input type="text" class="form-control" id="color" placeholder="Color" aria-label="Color">
                </div>
                <div class="col-sm" style="text-align: center;">
                    <label for="Tocar">Tocar</label>
                    <input type="text" class="form-control" id="tocar" placeholder="Tocar" aria-label="Tocar">
                </div>
            </div>
            <div class="col-sm">
                <label for="Teléfono">Teléfono</label>
                <input type="text" class="form-control" id="tel" placeholder="Teléfono" aria-label="Teléfono">
            </div>
        </div>
        <div style="display: flex; justify-content: end;">
            <button id="siguiente" class="btn btn-success">Siguiente</button>
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
                            <span>Asesor: {{ ucwords(Session::get('usuario')) }}</span>
                            <span>{{ $fecha }}</span>
                        </div>
                        <div class="mb-4">
                            <div>
                                <h4 class="text-center" id="nombreCliente"></h4>
                            </div>
                            <div class="text-center">
                                <h5 id="direccionT"></h5>
                            </div>
                            <div class="text-center">
                                <span style="font-weight: bold; text-decoration: underline;">Tocar:</span>
                                <span id="tocart"></span> <span id="colorT"></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 style="font-weight: bold; text-decoration: underline;">
                                <a style="text-decoration: none; color: black" id="telt"></a>
                            </h4>
                        </div>
                        <ul class="list-unstyled" style="margin: 0;">
                        </ul>
                        <p class="text-end fw-bold" style="margin-top: -10px;" id="total"></p>
                        <input type="text" id="totalAmount" value="" hidden>
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
                            <div class="col"><input id="input1" type="number" class="form-control" value=""></div>
                            <div class="col"><input id="input2" type="number" class="form-control" value=""></div>
                            <div class="col"><input id="input3" type="number" class="form-control" value=""></div>
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

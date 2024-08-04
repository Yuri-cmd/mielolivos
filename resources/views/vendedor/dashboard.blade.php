@extends('layout/vendedor-layout')
@section('content')
    @if ($gruposHoy->isEmpty())
        <div style="width: 100%; height: 90vh; display: flex; justify-content: center; align-items: center;">
            <div class=""
                style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; padding: 50px; text-align: center; border-radius: 20px;">
                <p>Hola {{ ucwords(Session::get('usuario')) }}. No hay grupos creados hoy a los que est&eacute;s asignado.
                </p>
            </div>
        </div>
    @else
        <div class="container">
            <div class="header">
                <div class="header-info">
                    <span style="font-weight: bold;">Asesor: {{ ucwords(Session::get('usuario')) }}</span>
                    <span style="font-weight: bold;">{{ $fecha }}</span>
                </div>
                <div class="header-detalle">
                    <h5 style="font-weight: bold;">Ventas del D&iacute;a</h5>
                    <h5 style="margin-left: 10px;font-weight: bold;">{{ $cantidadVentas }}</h5>
                </div>
            </div>
            <hr>
            <div class="sales">
                <div class="row">
                    @foreach ($productos1 as $p)
                        <div class="cell" style="font-weight: bold;">{{ $p->abreviatura }}</div>
                    @endforeach
                </div>
                <div class="row">
                    @foreach ($productos1 as $p)
                        <div class="cell"><input class="valor" data-id="{{ $p->id }}"
                                data-nombre="{{ ucwords($p->nombre) }}" data-precio="{{ $p->precio1 }}"
                                data-stock="{{ $p->sobrante }}" type="number" value="0" /></div>
                    @endforeach
                </div>
                <div class="row">
                    @foreach ($productos2 as $p)
                        <div class="cell" style="font-weight: bold;">{{ $p->abreviatura }}</div>
                    @endforeach
                </div>
                <div class="row">
                    @foreach ($productos2 as $p)
                        <div class="cell"><input class="valor" data-id="{{ $p->id }}"
                                data-nombre="{{ ucwords($p->nombre) }}" data-precio="{{ $p->precio1 }}"
                                data-stock="{{ $p->sobrante }}" type="number" value="0" /></div>
                    @endforeach
                </div>
                <div class="contado-checkbox">
                    <button class="btn btn-warning" id="contado">Contado</button>
                </div>
            </div>
            <hr>
            <div class="customer-info">
                <div class="col-sm">
                    <label for="Nombre" style="font-weight: bold;">Nombres y Apellidos</label>
                    <input type="text" class="form-control" id="nombre" value="" required />
                </div>
                <div class="row g-3">
                    <div class="col-sm" style="text-align: center;">
                        <label for="MZ" style="font-weight: bold;">MZ</label>
                        <input type="text" class="form-control" id="mz" placeholder="Mz" aria-label="Mz">
                    </div>
                    <div class="col-sm" style="text-align: center;">
                        <label for="Lt" style="font-weight: bold;">Lt</label>
                        <input type="text" class="form-control" id="lt" placeholder="Lt" aria-label="Lt">
                    </div>
                    <div class="col-sm-7" style="text-align: center;">
                        <label for="Jr./Calle" style="font-weight: bold;">Jr./Calle</label>
                        <input type="text" class="form-control" id="jr" placeholder="Jr./Calle"
                            aria-label="Jr./Calle">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm" style="text-align: center;">
                        <label for="Piso" style="font-weight: bold;">Piso</label>
                        <input type="text" class="form-control" id="piso" placeholder="Piso" aria-label="Piso">
                    </div>
                    <div class="col-sm" style="text-align: center;">
                        <label for="N&deg; Pisos" style="font-weight: bold;">N&deg; Pisos</label>
                        <input type="text" class="form-control" id="pisos" placeholder="N&deg; Pisos"
                            aria-label="N&deg; Pisos">
                    </div>
                    <div class="col-sm-7" style="text-align: center;">
                        <label for="Urbanizaci&oacute;n" style="font-weight: bold;">Urbanizaci&oacute;n</label>
                        <input type="text" class="form-control" id="urb" placeholder="Urbanizaci&oacute;n"
                            aria-label="Urbanizaci&oacute;n">
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm" style="text-align: center;">
                        <label for="Color" style="font-weight: bold;">Color</label>
                        <input type="text" class="form-control" id="color" placeholder="Color"
                            aria-label="Color">
                    </div>
                    <div class="col-sm" style="text-align: center;">
                        <label for="Tocar" style="font-weight: bold;">Tocar</label>
                        <input type="text" class="form-control" id="tocar" placeholder="Tocar"
                            aria-label="Tocar">
                    </div>
                </div>
                <div class="col-sm">
                    <label for="Tel&eacute;fono" style="font-weight: bold;">Tel&eacute;fono</label>
                    <input type="text" class="form-control" id="tel" placeholder="Tel&eacute;fono"
                        aria-label="Tel&eacute;fono">
                </div>
            </div>
            <div style="display: flex; justify-content: end;">
                <button id="siguiente" class="btn btn-success">Siguiente</button>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
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
                                    <h5><span id="jrT"></span>/ <span id="mzT"></span>/ <span
                                            id="lotT"></span> / <span id="urbT"></span></h5>
                                </div>
                                <div class="text-center">
                                    <span style="font-weight: bold; text-decoration: underline;" id="pisoT"></span> /
                                    <span style="font-weight: bold; text-decoration: underline;" id="pisosT"></span>
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
                                <div class="col cuotas" id="input3ContainerFecha" style="display: none;">
                                    <span>2DA CUOTA</span>
                                    <span>
                                        {{ $formatoFecha2 }}
                                    </span>
                                </div>
                                {{-- <div class="col">Pendiente</div> --}}
                            </div>
                            <div class="row text-center" style="margin-top: -10px;">
                                <div class="col"><input id="input1" type="number" class="form-control"
                                        value=""></div>
                                <div class="col"><input id="input2" type="number" class="form-control"
                                        value=""></div>
                                <div class="col" id="input3-container" style="display: none;"><input id="input3"
                                        type="number" class="form-control" value="" readonly></div>
                            </div>
                            <div class="firma mt-3" style="display: flex; flex-direction: column; align-items: center;">
                                <div style="display: flex; justify-content: center;">
                                    <label for="firma">Firma:</label>
                                    <canvas id="signature-pad" class="signature-pad" width="400"
                                        height="200"></canvas>
                                </div>
                                <button class="btn btn-secondary mt-2" id="clear">Borrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atr&aacute;s</button>
                        <button type="button" class="btn btn-primary" id="submit"
                            data-idGrupo="{{ $gruposHoy[0]->id }}">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ContadoModal" tabindex="-1" aria-labelledby="ContadoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ContadoModalLabel">Detalle de Venta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="sales-summary">
                            <div class="header-info mb-2" style="">
                                <span>Asesor: {{ ucwords(Session::get('usuario')) }}</span>
                                <span>{{ $fecha }}</span>
                            </div>
                            <div class="mt-8">
                                <ul class="list-unstyled" style="margin: 0;">
                                </ul>
                            </div>
                            <p class="text-end fw-bold" style="margin-top: -10px;" id="totalc"></p>
                            <input type="text" id="totalAmountc" value="" hidden>
                        </div>
                        <div class="payment-details">
                            <div class="row text-center"
                                style="border: 1px solid red;padding: 10px;border-radius: 10px;rgba(99, 99, 99, 0.2) 0px 2px 8px 0px">
                                <h2 class="text-danger">Cancelado</h2>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Atr&aacute;s</button>
                        <button type="button" class="btn btn-primary" id="submitContado"
                            data-idGrupo="{{ $gruposHoy[0]->id }}">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

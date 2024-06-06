@extends('layout/admin-layout')
@section('content')
    <div class="contenedor">
        <div style="width: 20%; padding: 0 10px;">
            <div style="display: flex; align-items: center;">
                <div style="margin: 10px;">
                    <i id="search-icon" class="bi bi-search"
                        style="font-size: 1.5rem; color: cornflowerblue; cursor: pointer;"></i>
                </div>
                <div style="margin: 10px;">
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div id="search-container" style="margin: 10px; display: none;">
                    <input type="text" class="form-control" id="search-input" placeholder="Buscar...">
                </div>
            </div>
            <div>
                <table class="table table-striped table-hover" id="tabla-datos">
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">11</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">12</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">13</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">14</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">15</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">16</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">17</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                        <tr>
                            <th scope="row">18</th>
                            <td>Rosa Maria Palacios Sotil / Beanelmi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div
            style="display: flex; justify-content: space-between; width: 80%; padding: 58px 10px; border-left: 1px solid black;">
            <div class="container-cards" id="cardContainer">
                <div class="cardm cardscroll">
                    <span>CHENA</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>BEANELMI</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>CARLOS</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>BRAYAN</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>DARIANNY</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>SARA M</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>WILLIAM</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>SARA Z</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>PEDRO</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>ANA</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>GABRIEL</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardm cardscroll">
                    <span>LORIANY</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
            </div>
            <div style="width: 90%;">
                <div class="cardm" style="margin-bottom: 10px;">
                    <span>GCHENA</span>
                    <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                </div>
                <div class="cardH" id="cardho">
                    <div id="contenedorVendedores">
                        <div class="cardm" style="margin-bottom: 12px;">
                            <span>GCHENA</span>
                            <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                        </div>
                        <div class="cardm" style="margin-bottom: 12px;">
                            <span>GCHENA</span>
                            <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                        </div>
                        <div class="cardm" style="margin-bottom: 12px;">
                            <span>GCHENA</span>
                            <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                        </div>
                        <div class="cardm" style="margin-bottom: 12px;">
                            <span>GCHENA</span>
                            <small>{{ $nombreDia }} {{ $dia }} {{ $mes }}</small>
                        </div>
                    </div>
                    <div class="contenedor-row" id="pcampo">
                        <div>
                            <span>P.CAMPO:</span>
                        </div>
                        <div class="cardm campo">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm campo">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm campo">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm campo">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm" id="totalCampo">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                    </div>
                    <div class="contenedor-row" id="pvendido">
                        <div>
                            <span>P.VENDIDOS:</span>
                        </div>
                        <div class="cardm vendidos">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm vendidos">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm vendidos">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm vendidos">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm" id="totalVendido">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                    </div>
                    <div class="contenedor-row">
                        <div>
                            <span>P.SOBRANTES:</span>
                        </div>
                        <div class="cardm sobrantes">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm sobrantes">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm sobrantes">
                            <input type="number" class="form-control" inputmode="numeric">
                        </div>
                        <div class="cardm sobrantes">
                            <input type="number" class="form-control" inputmode="numeric">
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
                                <input type="text" class="form-control" style="width: 50px;">
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                            <div style="margin-right: 10px;">
                                <span>TAXI:</span>
                            </div>
                            <div class="cardm">
                                <input type="text" class="form-control" style="width: 50px;">
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                            <div style="margin-right: 10px;">
                                <span>EFECTIVO:</span>
                            </div>
                            <div class="cardm">
                                <input type="text" class="form-control" style="width: 50px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

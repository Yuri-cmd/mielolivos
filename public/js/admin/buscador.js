$('#buscador').click(function (){
    $('#buscadorModal').modal('show');
    $.get(urlGetVentas, function (data, textStatus, jqXHR) {
        let tbody = '';
        $.each(data, function (i, v) { 
            tbody += `<tr data-id="${v.id}" class="verDetalle" style="cursor:pointer">`;
            tbody += `<th>${ i + 1}</th>`;
            tbody += `<td>${v.nombre}</td>`;
            tbody += `<td>${v.usuario}</td>`;
            tbody += `</tr>`;
        });
        $('#tableBuscador tbody').html(tbody);
        new DataTable('#tableBuscador');
    });
});

$(document).on('click', '.verDetalle', function (){
    let id = $(this).data('id');
    $.post(urlDetalleVenta, {
        _token: token,
        id: id
    },
        function (data, textStatus, jqXHR) {
            $('#buscadorModal').modal('hide');
            $('#buscadorDetalle').modal('show');
            let vendedor = data.vendedor;
            let venta = data.venta;
            let detalle = data.detalle;
            let cuotas = data.cuotas[0];
            let lista = '';
            let total = 0;
            $('#asesor').html(vendedor.usuario);
            $('#fechaVenta').html(formatDate(venta.fecha));
            $('#cliente').html(venta.nombre);
            $('#jr').html('JR. '+venta.jr);
            $('#urb').html('Urb. '+venta.urb);
            $('#piso').html('Piso: '+(venta.piso ? venta.piso : '-'));
            $('#pisos').html('N°Pisos: '+(venta.pisos ? venta.pisos : '-'));
            $('#color').html('Color: '+(venta.color ? venta.color : '-'));
            $('#tocar').html((venta.tocar ? venta.tocar : '-'));
            $('#telefono').html(venta.telefono);
            $('#telefono').attr('href', 'tel:'+venta.telefono);

            $.each(detalle, function (i, v) { 
                precio = parseFloat(v.precio) * v.cantidad;
                total += precio;
                lista += `<li><span>${v.cantidad} ${capitalizeFirstLetter(v.nombre)}</span> <span>S/${precio.toFixed(2)}</span></li>`;
            });
            $('.list-unstyled').html(lista);
            $('#total').html('S/'+total.toFixed(2));
            $('#firma').attr('src', 'http://mielolivos.test/storage/signatures/'+venta.firma);
            $('#fecha1').html(data.formatoFecha);
            $('#fecha2').html(data.formatoFecha1);
            $('#fecha3').html(data.formatoFecha2);
            $('#cuota1').val(parseFloat(cuotas.cuota1).toFixed(2));
            $('#cuota2').val(parseFloat(cuotas.cuota2).toFixed(2));
            $('#cuota3').val(parseFloat(cuotas.cuota3).toFixed(2));

            let pagado = 0;
            let row = '';
            $.each(data.pagos, function (i, v) { 
                let pendientetd = v.pendiente !== '0' && v.pendiente !== '0.00' ? `<td><span class="precios-cobrador">${parseFloat(v.pendiente).toFixed(2)}</span></td>` : '';
                row += `
                    <tr>
                        <td>${v.creado_el}</td>
                        <td><span class="precios-cobrador">${parseFloat(v.abono).toFixed(2)}</span></td>
                        ${pendientetd}
                    </tr>
                `;
                pagado += parseFloat(v.abono);
            });
            $('#payment-table-body').html(row);
            let restante = (parseFloat(cuotas.cuota2) + parseFloat(cuotas.cuota3)) - pagado;
            console.log(restante);
            $('#restante').val(restante);
            $('#idVenta').val(venta.id);
            if(venta.estado == '1'){
                $('#cancelado').show();
                $('#abonoform').hide();
                $('#submit').hide();
            }

        },
    ); 
});
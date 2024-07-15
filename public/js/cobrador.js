let tabla = $('#datatable').DataTable();
// Función para filtrar la tabla y cambiar estilos de botones
function filterTable(filter, button) {
    $.ajax({
        url: urlFilter,
        method: 'GET',
        data: { filter: filter },
        success: function(response) {
            // Limpiar la tabla
            tabla.clear().draw();
            
            // Agregar las nuevas filas
            response.ventas.forEach(function(venta, index) {
                let total = venta.deuda - venta.total_pagos;
                let bg =  total == 0 ? 'text-bg-success' : 'text-bg-secondary';
                tabla.row.add([
                    index + 1,
                    `<span class="badge${venta.color_vencimiento}">${venta.nombre}</span> <br><small>${venta.usuario}</small>`,
                    `<span class="badge ${bg}">${total}</span>`,
                    `<span class="badge ${venta.color_vencimiento}">${venta.dias_desde_venta}</span>`
                ]).draw(false);
            });

            // Cambiar estilos de los botones
            if (button.hasClass('button-selected')) {
                button.removeClass('button-selected');
            } else {
                $('.filter-button').removeClass('button-selected'); // Desactivar otros botones
                button.addClass('button-selected');
            }
        },
        error: function() {
            console.log('Error al filtrar los datos');
        }
    });
}

// Manejar clics en los botones de filtro
$('#filter-cuota1').on('click', function() {
    filterTable('1RA Cuota', $(this));
});

$('#filter-cuota2').on('click', function() {
    filterTable('2DA Cuota', $(this));
});

function mostrar(id){
    $("#exampleModal").modal('show');
    $.post(urlDetalleVenta, {
        _token: token,
        id: id
    },
        function (data, textStatus, jqXHR) {
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
}
// $('#submit').click(function() {
//     Swal.fire({
//         title: "Se guardo Correctamente",
//         text: "¿Desea enviar recibo?",
//         icon: "success",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Enviar",
//         cancelButtonText: "No, enviar"
//     }).then((result) => {
//         if (result.isConfirmed) {
//             Swal.fire({
//                 title: "Enviado!",
//                 text: "Se envio correctamente",
//                 icon: "success"
//             });
//         }
//     });
// });

    const $paymentTableBody = $('#payment-table-body');
    function updateTable(date, abono, pendiente) {
        let pendientetd = pendiente !== 0 && pendiente !== 0.00 ? `<td><span class="precios-cobrador">${parseFloat(pendiente).toFixed(2)}</span></td>` : '';
        const row = `
            <tr>
                <td>${date}</td>
                <td><span class="precios-cobrador">${parseFloat(abono).toFixed(2)}</span></td>
                ${pendientetd}
            </tr>
        `;
        $paymentTableBody.append(row);
    }

    $('#submit').on('click', function() {
        const totalAmount = parseFloat($('#restante').val());
        let remainingAmount = totalAmount;
        const $abonoInput = $('#abono');
        const abono = parseFloat($abonoInput.val());

        if (isNaN(abono) || abono <= 0) {
            alert('Por favor, ingrese un monto válido.');
            return;
        }

        if (abono > remainingAmount) {
            alert('El abono no puede exceder el monto pendiente.');
            return;
        }

        const date = new Date().toLocaleDateString('es-ES', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        
        remainingAmount -= abono;
        $('#restante').val(remainingAmount)
        updateTable(date, abono, remainingAmount);

        if (remainingAmount === 0) {
            alert('El pago ha sido completado.');
            $.post(urlUpdateEstadoVenta, {_token: token, idVenta: $('#idVenta').val()},
                function (data, textStatus, jqXHR) {
                    
                },
            );
            $('#cancelado').show();
            $('#abonoform').hide();
            $('#submit').hide();
        }

        // Reset the input field
        $abonoInput.val('');
        // Save the data to the database using AJAX
        savePaymentToDatabase(date, abono, remainingAmount);
    });

    function savePaymentToDatabase(date, abono, pendiente) {
        $.ajax({
            url: urlSavePago,
            method: 'POST',
            data: {
                _token: token,
                fecha: date,
                abono: abono,
                pendiente: pendiente,
                idVenta: $('#idVenta').val()
            },
            success: function(response) {
                console.log('Success:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

function formatDate(fechaOriginal) { 
    // Crear un objeto Date
    const fecha = new Date(fechaOriginal);

    // Obtener el nombre del día en español
    const opcionesDia = { weekday: 'long' };
    const nombreDia = fecha.toLocaleDateString('es-ES', opcionesDia);

    // Obtener el día del mes
    const diaMes = fecha.getDate();

    // Obtener el nombre del mes en español
    const opcionesMes = { month: 'long' };
    const nombreMes = fecha.toLocaleDateString('es-ES', opcionesMes);

    // Formatear la fecha
    return fechaFormateada = `${nombreDia.charAt(0).toUpperCase() + nombreDia.slice(1)} ${diaMes} ${nombreMes}`;

}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
$(document).ready(function() {
    let tablesO = $('#bOficina').DataTable({
        columns: [
            { title: "#" },
            { title: "Cliente" },
            { title: "Abono" },
            { title: "Pendiente" },
            { title: "Productos" }
        ]
    });

    $('#oficina').click(function (){
        $.get(urlGetVentasOficina, function (data, textStatus, jqXHR) {
            let productos1T = '';
            let productos2T = '';
            let products1Detail = '';
            let products2Detail = '';

            $.each(data.productos1, function (i, v) { 
                productos1T += `<div class="cell" style="font-weight: bold;">${v.abreviatura}</div>`;
                products1Detail +=`<div class="cell"><input class="valor" data-id="${v.id}"
                                data-nombre="${capitalizeFirstLetter(v.nombre)}" data-precio="${v.precio1}"
                                type="number" value="0" /></div>`;
            });
            $('#products1').html(productos1T);
            $('#products1Detail').html(products1Detail);
            $.each(data.productos2, function (i, v) { 
                productos2T += `<div class="cell" style="font-weight: bold;">${v.abreviatura}</div>`;
                products2Detail +=`<div class="cell"><input class="valor" data-id="${v.id}"
                                data-nombre="${capitalizeFirstLetter(v.nombre)}" data-precio="${v.precio1}"
                                type="number" value="0" /></div>`;
            });
            $('#products2').html(productos2T);
            $('#products2Detail').html(products2Detail);

            // Limpiar la tabla antes de agregar nuevos datos
            tablesO.clear();

            $.each(data.ventasOficina, function (i, v) {
                tablesO.row.add([
                    `<div class="row-click" data-id="${v.idVenta}">${i + 1}</div>`,
                    `<div class="row-click" data-id="${v.idVenta}">${capitalizeFirstLetter(v.cliente)}<br>${v.fecha}</div>`,
                    `<div class="row-click" data-id="${v.idVenta}">${v.abono}</div>`,
                    `<div class="row-click" data-id="${v.idVenta}">${v.pendiente}</div>`,
                    `<div class="row-click" data-id="${v.idVenta}">${v.productos}</div>`
                ]).draw();
            });
        });
        $('#oficinaModal').modal('show');
    });

    $('#enviarVentaOficina').click(function (){
        let cliente = $('#clienteO').val();
        let flag = true;
        let productos = $('.valor');
        let detalle = [];
        let abono = $('#abonoO').val();
        if(cliente == ''){
            Swal.fire("Ingrese el nombre del cliente.");
            return;
        }
        $(productos).each(function (index, element) {
            if($(element).val() > 0){
                detalle.push({
                    cantidad: $(element).val(),
                    id: $(element).attr("data-id"), 
                    precio: $(element).attr("data-precio"), 
                    nombre: $(element).attr("data-nombre"), 
                });
                flag = false;
            }
        }); 
        if(flag){
            Swal.fire("No se asignaron cantidades");
            return;
        }
        $.post(urlSaveVentasOficina, {
                _token: token,
                cliente: cliente,
                detalle: detalle,
                abono: abono
            },
            function (data, textStatus, jqXHR) {
                Swal.fire({
                    title: "Guardado",
                    text: "",
                    icon: "success"
                });
                console.log(data.abono);
                // Actualizar la tabla después de guardar
                tablesO.row.add([
                    `<div class="row-click" data-id="${data.idVenta}">${data.idVenta}</div>`,
                    `<div class="row-click" data-id="${data.idVenta}">${capitalizeFirstLetter(data.cliente)}<br>${data.fecha}</div>`,
                    `<div class="row-click" data-id="${data.idVenta}">${data.abono}</div>`,
                    `<div class="row-click" data-id="${data.idVenta}">${data.pendiente}</div>`,
                    `<div class="row-click" data-id="${data.idVenta}">${data.productos}</div>`
                ]).draw();

                $(productos).each(function (index, element) {
                    $(element).val(0);
                });
                $('#abonoO').val('');
                $('#clienteO').val('');
            },
        );
    });

    $('#bOficina tbody').on('click', 'tr', function () {
        let idVenta = $(this).find('.row-click').data('id');
        $.post(urlgetVentasOficinaDetalle, {_token: token,idVenta: idVenta},
            function (data, textStatus, jqXHR) {
                let venta = data.venta;
                let lista = [];
                let total = 0;
                $('#nombreCliente').text(venta.nombre);
                $('#fechaOficina').text(formatDate(venta.fecha));

                $.each(data.ventaDetalle, function (i, v) { 
                    precio = parseFloat(v.precio) * v.cantidad;
                    total += precio;
                    lista += `<li style="display:flex;justify-content: space-between;"><span>${v.cantidad} ${v.nombre}</span> <span>S/${precio.toFixed(2)}</span></li>`;
                });
                $('.list-unstyled').html(lista);
                $('#totalO').html('S/'+total.toFixed(2));
                $('#oficinaModal').modal('hide');
                $('#oficinaDetalle').modal('show');

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
                $('#payment-table-bodyO').html(row);
                let restante = total - pagado;
                $('#restanteO').val(restante);
                $('#idVentaO').val(venta.id);
                if(venta.estado === 1){
                    $('#canceladoO').show();
                    $('#abonoform').hide();
                    $('#submitO').hide();
                }
            },
        );
    });
});
    const $paymentTableBody = $('#payment-table-bodyO');
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

    $('#submitO').on('click', function() {
        const totalAmount = parseFloat($('#restanteO').val());
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
        $('#restanteO').val(remainingAmount)
        updateTable(date, abono, remainingAmount);

        if (remainingAmount === 0) {
            alert('El pago ha sido completado.');
            $.post(urlUpdateEstadoVenta, {_token: token, idVenta: $('#idVentaO').val()},
                function (data, textStatus, jqXHR) {
                    
                },
            );
            $('#canceladoO').show();
            $('#abonoform').hide();
            $('#submitO').hide();
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
                idVenta: $('#idVentaO').val()
            },
            success: function(response) {
                console.log('Success:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
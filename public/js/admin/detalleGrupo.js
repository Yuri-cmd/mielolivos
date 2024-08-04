     // Enviar datos al controlador
     $('#guardarBtn').on('click', function() {
        var datos = [];
        $('.stockMaster').each(function() {
            var idUsuario = $(this).data('idusuario');
            var stock = $(this).val();
            datos.push({ id: idUsuario, stock: stock });
        });

        $.ajax({
            url: urlMasterStock,  // Cambia esto a la ruta correcta
            type: 'POST',
            data: {
                _token: token,
                datos: datos
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Se guardo correctamente",
                    showCancelButton: false,
                    confirmButtonText: "Ok",
                  }).then((result) => {
                    $('#masterModal').modal('hide');
                  });
            },
            error: function(xhr, status, error) {
                // Maneja el error aquí
                alert('Ocurrió un error al guardar los datos');
            }
        });
    });

    
    
    $('#depositod, #taxid').on('blur', function() {
        var idGrupo = $('#idGrupoView').val();
        var deposito = $('#depositod').text().trim();
        var taxi = $('#taxid').text().trim();
        // Envía ambos valores al servidor
        $.post(urlUpdateDepositoTaxiGrupo, {
            _token: token,
            deposito: deposito,
            taxi: taxi,
            idGrupo: idGrupo
        }, function(data, textStatus, jqXHR) {
            Swal.fire("Actualizado");
        });
    });

    $('#miTabla').on('click', '.campoEdit', function() {
        let idUser = $(this).data('id');
        let idGrupo = $(this).data('grupo');
        $('#exampleModal').modal('hide');
        $.post(urlGetProductoMaster,{_token: token, idUser: idUser},
            function(data, textStatus, jqXHR) {
                let html = `<div class="productos-container">`;
                $.each(data, function(i, v) {
                    html += `
                        <div class="grid-container">
                            <div class="grid-item"> 
                                <span>${v.nombre}:</span>
                            </div>
                            <div class="cardm grid-item">
                                <input type="text" class="form-control" id="stock-${v.id}" value="${v.cantidad}">
                            </div>
                        </div>`;
                });
                html += `</div>`;
                // Mostrar SweetAlert con el contenido generado
                Swal.fire({
                    title: "<strong>Agregar productos</strong>",
                    icon: "info",
                    html: html,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: `Guardar`,
                    cancelButtonText: `Cerrar`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let postData = [];
                        $('.productos-container .grid-container').each(function() {
                            let stockId = $(this).find('.cardm input[type="text"]:first').attr('id');
                            let stockValue = $(this).find('.cardm input[type="text"]:first').val();
                            postData.push({
                                stock_id: stockId,
                                stock_value: stockValue,
                            });
                        });
                        $.post(urlUpdateCampoUsuario, {_token: token, idUsuario: idUser, data:JSON.stringify(postData)},
                            function (data, textStatus, jqXHR) {
                                visualizarGrupo(idGrupo);
                            },
                        );
                    } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                    ) {
                        visualizarGrupo(idGrupo);
                    }
                });
            },
        );
    });

    $('#miTabla').on('click', '.detalleVendedor', function() {
        $('#exampleModal').modal('hide');
        $('#detalleVendedorModal').modal('show');
        let idGrupoUsuario = $(this).data('id');
        $('#idGrupoUsuario').val(idGrupoUsuario);
        $.post(urlGetDetalleVendedor, {_token: token, idGrupoUsuario: idGrupoUsuario},
            function (data, textStatus, jqXHR) {
                $('#nombreVendedor').text(capitalizeFirstLetter(data.vendedor));
                $('#fechaVenta').text(data.fecha);
                let tbody = '';
                let totalContado = 0; 
                let contador = 1;
                let totalVendedor = 0;
                let totalCreditos = 0;
                $.each(data.ventas, function (i, v) { 
                    tbody += '<tr>';
                    tbody += `<th scope="row">${i+1}</th>`;
                    tbody += `<td>${v.nombre}</td>`;
                    tbody += `<td>${v.total_cantidad}</td>`;
                    tbody += '</tr>';
                    contador++;
                    totalVendedor += v.total_cantidad;
                    totalCreditos += v.total_cantidad;
                });

                $.each(data.contado, function (i, v) { 
                    totalContado+= v.total_cantidad;
                });
                if(data.contado.length > 0){
                    tbody += '<tr>';
                    tbody += `<th scope="row">${contador + 1}</th>`;
                    tbody += `<td>Contado</td>`;
                    tbody += `<td>${totalContado}</td>`;
                    tbody += '</tr>';
                    totalVendedor += totalContado;
                }

                let tbodyDetalle = '<tr>';
                tbodyDetalle+= `<td>${totalCreditos}</td>`;
                tbodyDetalle+= `<td>${totalContado}</td>`;
                tbodyDetalle+= `<td>0</td>`;
                tbodyDetalle+= `<td>${data.porCobrar}</td>`;
                tbodyDetalle+= `<td>100</td>`;
                tbodyDetalle+= `</tr>`;
                $('#tableDetalleVenta tbody').html(tbodyDetalle);

                $('#totalVendedor').html('Total: ' + totalVendedor);
                $('#tableDetalle tbody').html(tbody);
            },
        );
    });

    $('#cerrarDetalle').click(function (){
        $('#detalleVendedorModal').modal('hide');
        let id = $('#idGrupoUsuario').val();
        visualizarGrupo(id);
    });


    function visualizarGrupo(id){
        // let id = $(this).attr("data-id"); 
        $.post(urlGetGrupoDetalle, {_token:token, id:id},
            function (data, textStatus, jqXHR) {
                let datos = JSON.parse(data);
                let fecha = formatDate(datos.datoGrupo[0].fecha);
                $('.nameGrupo').text(datos.datoGrupo[0].nombre);
                $('.fechaGrupo').text(fecha);
                let tbody = '';
                let totalCampo = 0;
                let totalVendido = 0;
                let totalSobrante = 0;
                let label = [];
                let valores = [];
                $.each(datos.detalle, function (i, v) {
                    totalCampo += v.campo;
                    totalVendido += parseFloat(v.vendidos);
                    totalSobrante += v.sobrantes;
                    tbody += `<tr>
                                <td style="text-align: center; cursor: pointer;" class="detalleVendedor" data-id="${v.id}">${v.usuario}<br>${fecha}</td>
                                <td class="table-warning campoEdit" style="text-align: center; cursor: pointer;" data-grupo="${id}" data-id="${v.id}">${v.campo}</td>
                                <td cstyle="text-align: center">${v.vendidos}</td>
                                <td style="text-align: center">${v.sobrantes}</td>
                            </tr>`;
                    label.push(v.usuario);
                    valores.push(v.vendidos);
                });
                $('#idGrupoView').val(id);
                $('#detalleBody').html(tbody);
                $('#totalCampo').text(totalCampo);
                $('#totalVendido').text(totalVendido);
                $('#totalSobrante').text(totalSobrante);
                $('#depositod').text(datos.datoGrupo[0].deposito ?? "0");
                $('#taxid').text(datos.datoGrupo[0].taxi ?? "0");
                $('#efectivod').text(datos.ventaDetalle[0]?.efectivo ?? "0");
                $('#porcobrar').text(datos.ventaDetalle[0]?.cobrar ?? "0");
                $('#contado').text(datos.ventas[0]?.contado ?? "0");
                $('#creditos').text(datos.ventas[0]?.creditos ?? "0");
                const ctxDetalle = document.getElementById('bar').getContext('2d');
                const barDetalle = new Chart(ctxDetalle, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: '',
                            data: valores,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }],
                    },
                    options: {
                        indexAxis: 'x', // <-- here
                        responsive: true
                    }
                });
                $('#deleteGrupo').attr('data-id', id);
            },
        );
        $('#exampleModal').modal('show');
    }
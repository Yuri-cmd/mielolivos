
$(document).ready(function() {
    // Asignar cantidad masiva
    $('#stockMasivo').on('input', function() {
        var cantidad = $(this).val();
        $('.stockMaster').each(function() {
            $(this).val(cantidad);
        });
    });

    $('#master').click(function (){
        $('#masterModal').modal('show');
        $.get(urlGetMaster,
            function (data, textStatus, jqXHR) {
                let productos = JSON.parse(data);
                let tbody = "";
                $.each(productos, function (i, v) { 
                    tbody += "<tr>";
                    tbody += `<th scope="row">${ i + 1 }</th>`;
                    tbody += `<th scope="row">${capitalizeFirstLetter(v.nombre)}</th>`;
                    tbody += `<td><div class="cardm"><input type="number" class="form-control stockMaster" data-idUsuario='${v.id}' inputmode="numeric" value="${v.stock}">
                              </div></td></th>`;
                    tbody += `</tr>`;
                });
                $('#tableUsuarios tbody').html(tbody)
            },
        );
    });

    $('#cuadreGeneral').click(function (){
        $('#cuadreGeneralModal').modal('show');
    });

    $('#comision').click(function (){
        $.get(urlGetComision,
            function (data, textStatus, jqXHR) {
                let tbody = '';
                let productos = 0;
                let vendedor = 0;
                let lider = 0;
                let comision = 0;
                let descuentos = 0;
                let bono = 0;
                let pefectivo = 0;
                let pdeposito = 0;
                let pfinal = 0;
                $.each(data, function (i, v) { 
                    tbody += `<tr>`; 
                    tbody += `<th scope="row">${capitalizeFirstLetter(v.usuario)}</th>`;            
                    tbody += `<td class="editable" data-field="productos" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.productos}</td>`;            
                    tbody += `<td class="editable" data-field="vendedor" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.vendedor}</td>`;            
                    tbody += `<td class="editable" data-field="lider" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.lider}</td>`;            
                    tbody += `<td>S/${formatNumber(v.comision)}</td>`;            
                    tbody += `<td>S/${formatNumber(v.descuentos)}</td>`;            
                    tbody += `<td class="editable" data-field="bono" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.bono}</td>`;            
                    tbody += `<td class="editable" data-field="pefectivo" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.pefectivo}</td>`;            
                    tbody += `<td class="editable" data-field="pdeposito" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.pdeposito}</td>`;            
                    tbody += `<td class="editable" data-field="pfinal" data-idusuario="${v.idUsuario}" data-idcomision="${v.idComision}">${v.pfinal}</td>`;            
                    tbody += `</tr>`;
                    productos += parseFloat(v.productos);
                    vendedor += parseFloat(v.vendedor);
                    lider += parseFloat(v.lider);
                    comision += parseFloat(v.comision);
                    descuentos += parseFloat(v.descuentos);
                    bono += parseFloat(v.bono);
                    pefectivo += parseFloat(v.pefectivo);
                    pdeposito += parseFloat(v.pdeposito);
                    pfinal += parseFloat(v.pfinal);
                });
    
                tbody += `<tr>`; 
                tbody += `<th scope="row">TOTAL</th>`;            
                tbody += `<td>${productos}</td>`;            
                tbody += `<td>${vendedor}</td>`;            
                tbody += `<td>${lider}</td>`;            
                tbody += `<td>S/${formatNumber(comision)}</td>`;            
                tbody += `<td>S/${formatNumber(descuentos)}</td>`;            
                tbody += `<td>${bono}</td>`;            
                tbody += `<td>${pefectivo}</td>`;            
                tbody += `<td>${pdeposito}</td>`;            
                tbody += `<td>${pfinal}</td>`;            
                tbody += `</tr>`;
                $('#tcomision').html(tbody);
                $('#comisionModal').modal('show');
            },
        );
    });

    $(document).on('click', '.editable', function() {
        var $td = $(this);
        var originalValue = $td.text().trim();
        var field = $td.data('field');
        var idUsuario = $td.data('idusuario');
        let idcomision = $td.data('idcomision');
        $td.html('<input type="text" class="form-control" style="width: 60px;" value="' + originalValue + '"/>');
        $td.find('input').focus().blur(function() {
            var newValue = $(this).val();
            $td.text(newValue);
    
            if (originalValue !== newValue) {
                $.ajax({
                    url: urlUpdateComision, // Reemplaza con tu URL de actualización
                    method: 'POST',
                    data: {
                        _token: token, // Token CSRF
                        idUsuario: idUsuario,
                        idcomision: idcomision,
                        field: field,
                        value: newValue
                    },
                    success: function(response) {
                        console.log('Actualización exitosa');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la actualización');
                    }
                });
            }
        });
    });

    
});
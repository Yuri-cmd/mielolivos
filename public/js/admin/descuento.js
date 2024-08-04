// descuento
$('#descuento').click(function (){
    $.get(urlGetDescuento,
        function (data, textStatus, jqXHR) {
            let tbody = '';
            let envase = 0;
            let panos = 0;
            let pendiente = 0;
            let tardanza = 0;
            let total = 0;
            let parches = 0;
            $.each(data, function (i, v) { 
                tbody += `<tr>`; 
                tbody += `<th scope="row">${capitalizeFirstLetter(v.usuario)}</th>`;            
                tbody += `<td class="editable" data-field="envases" data-idusuario="${v.idUsuario}" data-iddescuento="${v.idDescuento}">${v.envases}</td>`;            
                tbody += `<td class="editable" data-field="panos" data-idusuario="${v.idUsuario}" data-iddescuento="${v.idDescuento}">${v.panos}</td>`;            
                tbody += `<td>S/${formatNumber(v.pendiente)}</td>`;            
                tbody += `<td class="editable" data-field="tardanza" data-idusuario="${v.idUsuario}" data-iddescuento="${v.idDescuento}">${v.tardanza}</td>`;            
                tbody += `<td>S/${formatNumber(v.total)}</td>`;            
                tbody += `<td class="editable" data-field="parches" data-idusuario="${v.idUsuario}" data-iddescuento="${v.idDescuento}">${v.parches}</td>`;            
                tbody += `</tr>`;
                envase += parseFloat(v.envases);
                panos += parseFloat(v.panos);
                pendiente += parseFloat(v.pendiente);
                tardanza += parseFloat(v.tardanza);
                total += parseFloat(v.total);
                parches += parseFloat(v.parches);
            });

            tbody += `<tr>`; 
            tbody += `<th scope="row">TOTAL</th>`;            
            tbody += `<td>${envase}</td>`;            
            tbody += `<td>${panos}</td>`;            
            tbody += `<td>S/${formatNumber(pendiente)}</td>`;            
            tbody += `<td>${tardanza}</td>`;            
            tbody += `<td>S/${formatNumber(total)}</td>`;            
            tbody += `<td>${parches}</td>`;            
            tbody += `</tr>`;
            $('#tdescuento').html(tbody);
            $('#descuentoModal').modal('show');
        },
    );
});

$(document).on('click', '.editable', function() {
    var $td = $(this);
    var originalValue = $td.text().trim();
    var field = $td.data('field');
    var idUsuario = $td.data('idusuario');
    let idDescuento = $td.data('iddescuento');
    $td.html('<input type="text" class="form-control" style="width: 60px;" value="' + originalValue + '"/>');
    $td.find('input').focus().blur(function() {
        var newValue = $(this).val();
        $td.text(newValue);

        if (originalValue !== newValue) {
            $.ajax({
                url: urlUpdateDescuento, // Reemplaza con tu URL de actualización
                method: 'POST',
                data: {
                    _token: token, // Token CSRF
                    idUsuario: idUsuario,
                    idDescuento: idDescuento,
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
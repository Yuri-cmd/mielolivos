// caja chica
$('#cajaChica').click(function (){
    $.get(urlGetCajaChica,
        function (data, textStatus, jqXHR) {
            let tbody = "";
            let total = 0;
            $.each(data.caja, function (i, v) { 
                tbody += "<tr>";
                tbody += `<th scope="row">${formatDate(v.fecha)}</th>`;
                tbody += `<td>${v.nombre}</td>`;
                tbody += `<td>S/${v.monto}</td>`;
                tbody += "</tr>";
                total += parseFloat(v.monto); 
            });
            $('#cajadetalle').html(tbody);
            $('#saldoCaja').text(`S/${parseFloat(data.saldo[0].saldo)}`);
            $('#montoCaja').html(`S/${total}`);
        },
    );
    $('#cajaChicaModal').modal('show');
});

$('#saldoCaja').on('blur', function() {
    let saldo = $('#saldoCaja').text().trim();
    if (saldo.indexOf('S/') === -1) {
        $('#saldoCaja').text('S/' + parseFloat(saldo));
    }else{
        $('#saldoCaja').text(saldo);
    }
    // Envía ambos valores al servidor
    $.post(urlUpdateSaldoCajaChica, {
        _token: token,
        saldo:saldo
    }, function(data, textStatus, jqXHR) {
    });
});

$('#agregarCaja').click(function() {
    // Crear una nueva fila editable
    const newRow = `
        <tr>
            <td style="font-weight:bold;">${formatDate(new Date())}</td>
            <td contenteditable="true" class="gasto"></td>
            <td contenteditable="true" class="total"></td>
            <td><button type="button" class="btn btn-success guardarFila"><i class="bi bi-floppy-fill"></i></button></td>
        </tr>`;
    $('#cajadetalle').append(newRow);
});

// Manejar el evento de clic del botón Guardar en la fila
$(document).on('click', '.guardarFila', function() {
    const row = $(this).closest('tr');
    const gasto = row.find('.gasto').text().trim();
    let total = row.find('.total').text().trim();
    let totalG = total;
    // Validar los datos antes de guardar (opcional)
    if (gasto === '' || total === '') {
        alert('Por favor, complete ambos campos: Gasto y Total.');
        return;
    }
    // Añadir el símbolo S/ delante del total si no lo tiene
    if (!total.startsWith('S/')) {
        total = 'S/' + parseFloat(total).toFixed(2);
    }

    // Actualizar el valor de la celda Total con el símbolo S/
    row.find('.total').text(total);
    guardarCaja(gasto,totalG);
    // Deshabilitar la edición después de guardar
    row.find('td').attr('contenteditable', 'false');
    $(this).remove();
});

function guardarCaja(nombreGasto, montoCaja){
    $.post(urlSaveCajaChica, {
        _token: token,
        nombreGasto: nombreGasto,
        montoCaja: montoCaja,
    },
        function (data, textStatus, jqXHR) {
            Swal.fire("Guardado!", "", "success").then(() => {
                let monto = $('#montoCaja').html().split('S/');
                monto = monto[1];
                let total = parseFloat(monto) + parseFloat(montoCaja);
                $('#montoCaja').html(`S/${total}`)
            });
        },
  );
}
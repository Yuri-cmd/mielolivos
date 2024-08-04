$(".cardscroll").draggable({
    helper: "clone"
});

// Hacer el contenedor de vendedores como una zona de soltado
var grupoCreado = false;

$("#contenedorGrupos").droppable({
    accept: ".cardscroll",
    drop: function(event, ui) {
        if (!grupoCreado) {
            let $card = $(ui.helper).clone();
            let nombreAsociado = $card.find("span").text().trim().split("x")[0]; 
            let nombreGrupo = "G" + nombreAsociado; 
            let fecha = $card.find("small").text().trim(); 
            let id = $card.data('id');
            // Crear el nuevo grupo con botón para borrar
            let nuevoGrupo = $("<div>", { "class": "cardm", "style": "position: relative; cursor: pointer;"})
                .append($("<span>").text(nombreGrupo))
                .append($("<span>", { "class": "close-button eliminar", "style": "cursor: pointer; position: absolute; top: 5px; right: 5px;" })
                    .text("x").click(function() {
                        location.reload();
                    }))
                .append($("<small>").text(fecha));

            let usuario = $("<div>", { "class": "cardm grupoUser", "data-id": id })
                .append($("<span>").text(nombreAsociado))
                .append($("<span>", { "class": "close-button eliminar", "style": "cursor: pointer; position: absolute; top: 5px; right: 5px;" })
                    .text("x").click(function() {
                        $(this).parent().remove();
                    }))
                .append($("<small>").text(fecha)); 
            
            $('#contenedorVendedores').append(usuario);
            $(this).append(nuevoGrupo);
            $('.grafica').hide();
            getStockMaster(id);
            // Mostrar el contenedor del grupo
            $('.grupo').show();
            $('.spanGrupo').html(nombreGrupo);

            // Actualizar el estado del grupo
            grupoCreado = true;
        } else {
            Swal.fire({
                title: "Info",
                text: "Ya se ha creado un grupo. No puede mover más elementos aquí hasta que se termine con la creación.",
                icon: "info"
            });
        }
    }
});

$("#contenedorVendedores").droppable({
    accept: ".cardscroll",
    drop: function(event, ui) {
        let $card = $(ui.helper).clone();
        let nombreAsociado = $card.find("span").text().trim().split("x")[0];
        let fecha = $card.find("small").text().trim(); 
        let id = $card.data('id');
        // Evitar duplicados
        if ($("#contenedorVendedores .cardm span:contains('" + nombreAsociado + "')").length > 0) {
            Swal.fire({
                title: "Info",
                text: "Este vendedor ya ha sido agregado.",
                icon: "info"
            });
            return;
        }

        // Crear el nuevo elemento para el contenedor de vendedores con botón para borrar
        let nuevoAsociado = $("<div>", { "class": "cardm grupoUser", "data-id": id, "style": "margin-bottom: 10px; position: relative;" })
            .append($("<span>").text(nombreAsociado))
            .append($("<span>", { "class": "close-button eliminar", "style": "cursor: pointer; position: absolute; top: 5px; right: 5px;" })
                .text("x").click(function() {
                    $(this).parent().remove();
                }))
            .append($("<small>").text(fecha));

        getStockMaster(id);
        // Añadir el nuevo elemento a #contenedorVendedores
        $('#contenedorVendedores').append(nuevoAsociado);
    }
});



function initializeDraggable() {
    $(".cobrador").draggable({
        helper: "clone"
    });
}

function initializeDroppable() {
    $(".grupo").droppable({
        accept: ".cobrador",
        drop: function(event, ui) {
            const grupoId = $(this).data('id');
            const cobradorId = $(ui.draggable).data('id');

            $.ajax({
                url: asignarCobrador,
                type: 'POST',
                data: {
                    grupo_id: grupoId,
                    cobrador_id: cobradorId,
                    _token: token
                },
                success: function(response) {
                    alert('Cobrador asignado correctamente');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    });
}
initializeDraggable();
initializeDroppable();


$('#filterDate').on('change', function() {
    const filterDate = $(this).val();
    const contenedorGrupos = $('#contenedorGrupos');

    $.ajax({
        url: filtrarGrupos,
        type: 'POST',
        data: { _token:token,fecha: filterDate },
        success: function(data) {
            contenedorGrupos.empty();
            data.forEach(function(grupo) {
                const grupoDiv = `
                    <div class="cardm grupo" onclick="visualizarGrupo(${grupo.id})" data-id="${grupo.id}" style="cursor: pointer">
                        <span>${grupo.nombre}</span>
                        <small>${formatDate(grupo.fecha)}</small>
                    </div>
                `;
                contenedorGrupos.append(grupoDiv);
            });
            initializeDraggable();
            initializeDroppable();
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});


function getStockMaster(id){
    $.post(urlGetMasterUsuario, {
        _token: token,
        id: id
    },
    function (data, textStatus, jqXHR) {
        var pcampoDiv = $('<div>', {
            class: 'cardm',
            html: `<input type="number" class="form-control cantidades" inputmode="numeric" data-id="${id}" value="${data}">`
        });

        $('#pcampo').append(pcampoDiv);
    },
);
}

$("#contenedorVendedores .cardm span").append('<a href="#" class="eliminar">x</a>');

$('#submitAsociado').click(function(){
    $.ajax({
        url: urlCreateAsociado, // Ajusta la ruta según tu configuración
        method: 'POST',
        data: {
            usuario: $('#usuario').val(),
            clave: $('#clave').val(),
            _token: token
        },
        success: function(response) {
            $('#agregarAsociado').modal('hide'); 
            $('#formAsociado')[0].reset(); 
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: response.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
        error: function(response) {
            // Maneja los errores
            alert('Ocurrió un error al enviar los datos');
        }
    });
});

$('.deleteAsociado').click(function(){
    let id = $(this).attr("data-id"); 
    Swal.fire({
        title: "¿Estas seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlDeleteAsociado,
                method: 'POST',
                data: {
                    _token: token,
                    id: id
                },
                success: function(response) {
                    Swal.fire({
                        title: "Eliminado!",
                        text: response.message,
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });

});

$('#submitGrupo').click(function(){
    let nombre = $('.spanGrupo').text();
    let users = $('.grupoUser');
    let stocks = $('.cantidades');
    let vendedores = [];
    let idcobrador = $('.grupoCobrador').data('id');
    $(users).each(function (index, element) {
        let id = $(element).data('id');
        $(stocks).each(function (index, stock) {
            let stockId = $(stock).data('id');
            if(id == stockId){
                let cantidad = $(stock).val();
                vendedores.push({"id": id, "stock": cantidad});
            }
        });
        
    });
    $.ajax({
        url: urlCreateGrupo, // Ajusta la ruta según tu configuración
        method: 'POST',
        data: {
            _token: token,
            usuario: nombre,
            vendedores: JSON.stringify(vendedores),
            idcobrador: idcobrador
        },
        success: function(response) {
            Swal.fire({
                icon: "success",
                title: "Se guardo correctamente",
                showCancelButton: false,
                confirmButtonText: "Ok",
            }).then((result) => {
                location.reload();
            });
        },
        error: function(response) {
            // Maneja los errores
            alert('Ocurrió un error al enviar los datos');
        }
    });
});

$('#deleteGrupo').click(function(){
    let id = $(this).attr("data-id"); 
    Swal.fire({
        title: "¿Estas seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlDeleteGrupo,
                method: 'POST',
                data: {
                    _token: token,
                    id: id
                },
                success: function(response) {
                    Swal.fire({
                        title: "Eliminado!",
                        text: response.message,
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });

});

$('#submitCobrador').click(function(){
    $.ajax({
        url: urlCreateCobrador, // Ajusta la ruta según tu configuración
        method: 'POST',
        data: {
            usuario: $('#usuarioc').val(),
            clave: $('#clavec').val(),
            _token: token
        },
        success: function(response) {
            $('#agregarCobrador').modal('hide'); 
            $('#formCobrador')[0].reset(); 
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: response.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
        error: function(response) {
            // Maneja los errores
            alert('Ocurrió un error al enviar los datos');
        }
    });
});

$('.deleteCobrador').click(function(){
    let id = $(this).attr("data-id"); 
    Swal.fire({
        title: "¿Estas seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlDeleteCobrador,
                method: 'POST',
                data: {
                    _token: token,
                    id: id
                },
                success: function(response) {
                    Swal.fire({
                        title: "Eliminado!",
                        text: response.message,
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
    });

});

$('#usuarios').on('change', function() {
    let selectedValue = $(this).val();
    $('#grupoDetalle').modal('hide');
    var selectedText = $(this).find('option:selected').text().trim().toLowerCase();
    
    // Verificar si el usuario ya ha sido agregado
    var existeUsuario = $('#contenedorVendedores').find('.cardm span').filter(function() {
        return ($(this).text().trim().toLowerCase()).slice(0, -1) === selectedText;
    }).length > 0;

    if (!existeUsuario) {
        $.get(urlGetProducto,
            function(data, textStatus, jqXHR) {
                let html = `<div class="productos-container">`;
                $.each(data, function(i, v) {
                    html += `
                        <div class="grid-container">
                            <div class="grid-item"> 
                                <span>${v.nombre}:</span>
                            </div>
                            <div class="cardm grid-item">
                                <input type="text" class="form-control" id="stock-${v.id}" value="${v.stock}">
                            </div>
                            <div class="cardm grid-item">
                                <input type="text" class="form-control" id="input-${v.id}" value="0">
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
                    preConfirm: () => {
                        let errors = false;
                        $('.productos-container .grid-container').each(function() {
                            let stockValue = parseFloat($(this).find('.cardm input[type="text"]:first').val());
                            let inputValue = parseFloat($(this).find('.cardm input[type="text"]:last').val());
                            let stock = $(this).find('.stock-input');
                            let valor = $(this).find('.input-input');
                            if(inputValue > stockValue){
                                errors = true;
                                stock.addClass('is-invalid');
                                valor.addClass('is-invalid');
                            }
                        });
                        if (errors) {
                            Swal.showValidationMessage(
                                'Verifica los valores ingresados. El valor ingresado no puede superar el stock actual.'
                            );
                            return false;
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let postData = [];
                        let partes = selectedValue.split('/');
                        let total = 0;
                        $('.productos-container .grid-container').each(function() {
                            let stockId = $(this).find('.cardm input[type="text"]:first').attr('id');
                            let inputId = $(this).find('.cardm input[type="text"]:last').attr('id');
                            let stockValue = $(this).find('.cardm input[type="text"]:first').val();
                            let inputValue = $(this).find('.cardm input[type="text"]:last').val();
                            total += parseFloat(inputValue);
                            postData.push({
                                stock_id: stockId,
                                input_id: inputId,
                                stock_value: stockValue,
                                input_value: inputValue
                            });
                        });
                        $.post(urlCreateDetalleGrupo, {_token: token,idGrupo: $('#idGrupo').val(),idUsuario: partes[0], data:JSON.stringify(postData)},
                            function (data, textStatus, jqXHR) {
                                 // Crear los divs correspondientes
                                var asociadoDiv = $('<div>', {
                                    class: 'cardm',
                                    style: 'margin-bottom: 12px;',
                                    html: '<span>' + selectedText.toUpperCase() + '<a href="#" class="eliminar">x</a></span><small>'+fecha+'</small>'
                                });

                                var pcampoDiv = $('<div>', {
                                    class: 'cardm campo',
                                    html: `<input type="number" class="form-control" inputmode="numeric" value="${total}">`
                                });
                        
                                var pvendidoDiv = $('<div>', {
                                    class: 'cardm vendidos',
                                    html: `<input type="number" class="form-control" inputmode="numeric" value="0">`
                                });
                        
                                var psobrantesDiv = $('<div>', {
                                    class: 'cardm sobrantes',
                                    html: `<input type="number" class="form-control" inputmode="numeric" value="0">`
                                });
                        
                                // Agregar los divs a sus contenedores respectivos
                                $('#contenedorVendedores').append(asociadoDiv);
                                $('#pcampo').append(pcampoDiv);
                                $('#pvendido').append(pvendidoDiv);
                                $('#psobrantes').append(psobrantesDiv);
                                calcularSumaCampo();
                                calcularSumaVendidos();
                                calcularSumaSobrantes();
                                $('#grupoDetalle').modal('show');
                            },
                        );
                    } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                    ) {
                    
                    }
                });
            },
        );
    }
});

// Función para calcular la suma de los valores de todos los inputs
function calcularSumaCampo() {
    var suma = 0;
    $(".campo input[type='number']").each(function() {
        var valor = parseFloat($(this).val());
        if (!isNaN(valor)) {
            suma += valor;
        }
    });
    // Actualizar el valor del input dentro de totalCampo con la suma
    $("#totalCampo input[type='number']").val(suma);
}

// Función para calcular la suma de los valores de todos los inputs
function calcularSumaVendidos() {
    var suma = 0;
    $(".vendidos input[type='number']").each(function() {
        var valor = parseFloat($(this).val());
        if (!isNaN(valor)) {
            suma += valor;
        }
    });
    // Actualizar el valor del input dentro de totalCampo con la suma
    $("#totalVendido input[type='number']").val(suma);
}

// Función para calcular la suma de los valores de todos los inputs
function calcularSumaSobrantes() {
    var suma = 0;
    $(".sobrantes input[type='number']").each(function() {
        var valor = parseFloat($(this).val());
        if (!isNaN(valor)) {
            suma += valor;
        }
    });
    // Actualizar el valor del input dentro de totalCampo con la suma
    $("#totalSobrante input[type='number']").val(suma);
}


$('#contenedorVendedores').on('click', '.eliminar', function() {
    var elementoAEliminar = $(this).closest('.cardm');
    elementoAEliminar.remove();
});

$('#contenedorVendedores').on('click', '.eliminar', function() {
    // Eliminar el elemento de Asociados (contenedorVendedores)
    var elementoAEliminar = $(this).closest('.cardm');
    elementoAEliminar.remove();

    // Eliminar elementos correspondientes en P.CAMPO, P.VENDIDOS y P.SOBRANTES
    $('#pcampo').find('.cardm').remove();
    $('#pvendido').find('.cardm').remove();
    $('#psobrantes').find('.cardm').remove();
});

$('#guardarDetalle').click(function (){
    let id = $('#idGrupo').val();
    let depo = $('#depo').val();
    let taxi = $('#taxi').val();
    let efectivo = $('#efectivo').val();
    $.post(urlSaveDetalle, {_token: token,id: id, depo:depo, taxi:taxi,efectivo:efectivo},
        function (data, textStatus, jqXHR) {
            Swal.fire({
                title: "Guardo Correctamente",
                text: "se guardo correctamente",
                icon: "success"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    location.reload();
                } 
            });
        },
    );
});
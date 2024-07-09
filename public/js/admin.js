let tabla = $('#example').DataTable({
    "ajax": {
        "url": urlGetProducto, 
        "dataSrc": ""
    },
    "columns": [{
            "data": "id"
        },
        {
            "data": "nombre"
        },
        {
            "data": "descripcion"
        },
        {
            "data": "precio1"
        },
        {
            "data": "precio2"
        },
        {
            "data": "stock"
        },
        {
            "data": "proveedor"
        },
        {
            "data": null,
            "render": function(data, type, row) {
                return `<button data-item="${row.id}" class="btn-re-stock btn btn-sm btn-warning" style="color:white;"><i class="bi bi-arrow-repeat"></i></button>`;
            }
        },
        {
            "data": null,
            "render": function(data, type, row) {
                return `<button data-item="${row.id}" class="btn-edt btn btn-sm btn-info" style="color:white;"><i class="bi bi-pencil-square"></i></button>`;
            }
        },
        {
            "data": null,
            "render": function(data, type, row) {
                return `<input type="checkbox" data-id="${row.id}" class="btnCheckEliminar">`;
            }
        },
    ]
});

$('.btnBorrar').on('click', function() {
    let ids = [];

    // Recorre todos los checkboxes seleccionados
    $('.btnCheckEliminar:checked').each(function() {
        ids.push($(this).data('id'));
    });

    if (ids.length > 0) {
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
                    url: urlUpdateEstadoProducto,
                    method: 'POST',
                    data: {
                        _token: token,
                        ids: ids
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Eliminado!",
                            text: "Eliminado correctamente",
                            icon: "success"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                tabla.ajax.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
          });
    } else {
        Swal.fire({
            title: "Info",
            text: "Selecciona al menos un elemento para eliminar.",
            icon: "info"
        });
    }
});

$('#example').on('click', '.btn-re-stock', function() {
    let itemId = $(this).data('item');
    $('#itemId').val(itemId); 
    $('#actualizarStock').modal('show'); 
});

$('#updateStock').click(function (){
    let stock = $('#nstock').val();
    let id = $("#itemId").val();
    if(stock){
        $.ajax({
            url: urlUpdateStockProducto,
            method: 'POST',
            data: {
                _token: token,
                id: id,
                stock: stock
            },
            success: function(response) {
                Swal.fire({
                    position: "bottom-end",
                    icon: "success",
                    title: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#nstock').val('');
                $('#actualizarStock').modal('hide'); 
                tabla.ajax.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }else{
        Swal.fire({
            title: "Info",
            text: "Debe ingresar una cantidad.",
            icon: "info"
        });
    }
});

$('#example').on('click', '.btn-edt', function() {
    // Obtener los datos de la fila seleccionada
    let data = tabla.row($(this).closest('tr')).data();
    
    // Llenar el formulario del modal con los datos del producto seleccionado
    $('#editId').val(data.id);
    $('#editNombre').val(data.nombre);
    $('#editDescripcion').val(data.descripcion);
    $('#editPrecio1').val(data.precio1 ? parseFloat(data.precio1).toFixed(2) : '0.00');
    $('#editPrecio2').val(data.precio2 ? parseFloat(data.precio2).toFixed(2) : '0.00');
    $('#editStock').val(data.stock);
    $('#editProveedor').val(data.proveedor);

    // Mostrar el modal de editar producto
    $('#editarProductoModal').modal('show');
});

$('#btnGuardarCambios').on('click', function() {
    // Obtener los datos del formulario
    let id = $('#editId').val();
    let nombre = $('#editNombre').val();
    let descripcion = $('#editDescripcion').val();
    let precio1 = $('#editPrecio1').val();
    let precio2 = $('#editPrecio2').val();
    let stock = $('#editStock').val();
    let proveedor = $('#editProveedor').val();

    // Validar que todos los campos requeridos estén llenos
    if (!nombre || !descripcion || !precio1 || !stock || !proveedor) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son requeridos',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Preparar los datos para enviarlos al servidor
    let datos = {
        id: id,
        nombre: nombre,
        descripcion: descripcion,
        precio1: parseFloat(precio1),
        precio2: parseFloat(precio2) || null,
        stock: parseInt(stock),
        proveedor: proveedor,
        _token: token
    };
    // Realizar la solicitud AJAX para actualizar los datos del producto
    $.ajax({
        url: urlUpdateProducto,
        method: 'POST',
        data: datos,
        success: function(response) {
            // Cerrar el modal después de guardar los cambios
            $('#editarProductoModal').modal('hide');

            // Refrescar el DataTable para mostrar los cambios
            tabla.ajax.reload();

            // Mostrar alerta de éxito
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Producto actualizado correctamente',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        },
        error: function(xhr) {
            // Mostrar alerta de error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al actualizar el producto',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            console.error(xhr.responseText);
        }
    });
});

$('#btnAgregarProducto').on('click', function() {
    // Obtener los datos del formulario
    let nombre = $('#nombre').val();
    let descripcion = $('#descripcion').val();
    let precio1 = $('#precio1').val();
    let precio2 = $('#precio2').val();
    let stock = $('#stock').val();
    let proveedor = $('#proveedor').val();

    // Validar que todos los campos requeridos estén llenos
    if (!nombre || !descripcion || !precio1 || !stock || !proveedor) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son requeridos',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Preparar los datos para enviarlos al servidor
    let datos = {
        nombre: nombre,
        descripcion: descripcion,
        precio1: parseFloat(precio1),
        precio2: parseFloat(precio2) || null,
        stock: parseInt(stock),
        proveedor: proveedor,
        _token: token // Asegúrate de incluir el token CSRF
    };

    // Realizar la solicitud AJAX para agregar el producto
    $.ajax({
        url: urlCreateProducto, // Ajusta la ruta según tu configuración
        method: 'POST',
        data: datos,
        success: function(response) {
            // Cerrar el modal después de agregar el producto
            $('#modal-add-prod').modal('hide');

            // Refrescar el DataTable para mostrar los cambios
            tabla.ajax.reload();

            // Mostrar alerta de éxito con Swal.fire
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Producto agregado correctamente',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });

            // Limpiar los campos del formulario después de agregar
            $('#formAgregarProducto')[0].reset();
        },
        error: function(xhr) {
            // Mostrar alerta de error con Swal.fire
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al agregar el producto',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            console.error(xhr.responseText);
        }
    });
});

$(document).ready(function() {
    $('#search-icon').click(function() {
        $('#search-container').toggle();
        if ($('#search-container').is(':visible')) {
            $('#search-input').focus();
        }
    });

    function filtrarTabla() {
        var textoBusqueda = $("#search-input").val().toLowerCase();
        $("#tabla-datos tbody tr").each(function() {
            var textoFila = $(this).text().toLowerCase();
            // Mostrar o ocultar la fila según si coincide con el texto de búsqueda
            $(this).toggle(textoFila.indexOf(textoBusqueda) > -1);
        });

        // Verificar si se encontraron filas después del filtrado
        var filasMostradas = $("#tabla-datos tbody tr:visible").length;
        if (filasMostradas === 0) {
            // No se encontraron coincidencias, mostrar mensaje
            Swal.fire("No se encontraron coincidencias");
        }
    }

    // Escuchar el evento input en el campo de búsqueda
    $("#search-input").on("input", function() {
        filtrarTabla();
    });

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

    $("#contenedorCobradores").droppable({
        accept: ".cardscroll",
        drop: function(event, ui) {
            if ($("#contenedorCobradores .cardm").length > 0) {
                Swal.fire({
                    title: "Info",
                    text: "Solo puede agregar un cobrador.",
                    icon: "info"
                });
                return;
            }

            let $card = $(ui.helper).clone();
            let nombreCobrador = $card.find("span").text().trim().split("x")[0];
            let fecha = $card.find("small").text().trim(); 
            let id = $card.data('id');

            let nuevoCobrador = $("<div>", { "class": "cardm grupoCobrador", "data-id": id, "style": "margin-bottom: 10px; position: relative;" })
                .append($("<span>").text(nombreCobrador))
                .append($("<span>", { "class": "close-button eliminar", "style": "cursor: pointer; position: absolute; top: 5px; right: 5px;" })
                    .text("x").click(function() {
                        $(this).parent().remove();
                    }))
                .append($("<small>").text(fecha));

            $('#contenedorCobradores').append(nuevoCobrador);
        }
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

    function resetearElemento() {
        $("#contenedorVendedores").children().last().removeClass().removeAttr("style");
        $("#contenedorVendedores").children().last().addClass("cardm");
        $("#contenedorVendedores").children().last().css("margin-bottom", "12px");
        $("#contenedorVendedores .cardm:last-child span").append('<a href="#" class="eliminar">x</a>');
        let nuevoDiv = $(
            '<div class="cardm"><input type="number" class="form-control" inputmode="numeric"></div>');
        // Adjuntar el evento change al nuevo input de tipo número
        nuevoDiv.find("input[type='number']").on("change", function() {
            calcularSuma();
        });
        $("#totalCampo, #totalVendido, #totalSobrante").before(nuevoDiv);
    }

    
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
                let usuarios = JSON.parse(data);
                let tbody = "";
                $.each(usuarios, function (i, v) { 
                    tbody += "<tr>";
                    tbody += `<th scope="row">${ i + 1 }</th>`;
                    tbody += `<th scope="row">${capitalizeFirstLetter(v.usuario)}</th>`;
                    tbody += `<td><div class="cardm"><input type="number" class="form-control stockMaster" data-idUsuario='${v.id}' inputmode="numeric" value="${v.stock}">
                              </div></td></th>`;
                    tbody += `</tr>`;
                });
                $('#tableUsuarios tbody').html(tbody)
            },
        );
    });
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

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
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

    $('#miTabla').on('blur', '.campoEdit', function() {
        let idGrupo = $('#idGrupoView').val();
        let campo = $(this).text().trim();
        let idUser = $(this).data('id');
        $.post(urlUpdateCampoUsuario, {
            _token: token,
            idGrupo: idGrupo,
            idUsuario: idUser,
            campo: campo
        }, function(data, textStatus, jqXHR) {
            // Manejar la respuesta del servidor si es necesario
            Swal.fire("Actualizado");
        }).fail(function(xhr, status, error) {
            console.error('Error al actualizar:', error);
            Swal.fire("Error al actualizar");
        });
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

    $('#buscador').click(function (){
        $('#buscadorModal').modal('show');
        $.get(urlGetVentas,
            function (data, textStatus, jqXHR) {
                let tbody = '';
                $.each(data, function (i, v) { 
                    tbody += '<tr>';
                    tbody += `<th>${ i + 1}</th>`
                    tbody += `<td>${v.nombre}</td>`;
                    tbody += `<td>${v.usuario}</td>`;
                    tbody += `</tr>`;
                });
                $('#tableBuscador tbody').html(tbody);
                new DataTable('#tableBuscador');
            },
        );
    });
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
                            <td class="table-warning campoEdit" style="text-align: center" data-id="${v.id}" contenteditable="true">${v.campo}</td>
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
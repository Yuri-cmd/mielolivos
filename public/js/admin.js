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
    $("#contenedorVendedores").droppable({
        accept: ".cardscroll",
        drop: function(event, ui) {
            var $card = $(ui.helper).clone();
            $card.removeClass("ui-draggable-dragging").draggable({
                helper: "clone"
            });
            $(this).append($card);
            resetearElemento();
        }
    });

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
        let nombre = $('#nombre').val();
        $.ajax({
            url: urlCreateGrupo, // Ajusta la ruta según tu configuración
            method: 'POST',
            data: {
                usuario: $('#nombre').val(),
                _token: token
            },
            success: function(response) {
                $('#agregarGrupo').modal('hide'); 
                $('#formGrupo')[0].reset(); 
                $('#grupoDetalle').modal("show")
                $('#idGrupo').val(response.id);
            },
            error: function(response) {
                // Maneja los errores
                alert('Ocurrió un error al enviar los datos');
            }
        });
        $('#agregarGrupo').modal('hide'); 
        $('#formGrupo')[0].reset(); 
        $('#grupoDetalle').modal("show");
        $('.tituloGrupo').text('Grupo: '+nombre);
        $('.spanGrupo').text(nombre);
    });

    $('.deleteGrupo').click(function(){
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
                                    <input type="text" class="form-control" id="input-${v.id}" value="">
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

    $('.visualizar').click(function () {
        let id = $(this).attr("data-id"); 
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
                                <td style="text-align: center">${v.usuario}<br>${fecha}</td>
                                <td class="table-warning" style="text-align: center">${v.campo}</td>
                                <td cstyle="text-align: center">${v.vendidos}</td>
                                <td style="text-align: center">${v.sobrantes}</td>
                            </tr>`;
                    label.push(v.usuario);
                    valores.push(v.vendidos);
                });
                $('#detalleBody').html(tbody);
                $('#totalCampo').text(totalCampo);
                $('#totalVendido').text(totalVendido);
                $('#totalSobrante').text(totalSobrante);
                $('#depositod').text(datos.datoGrupo[0].deposito);
                $('#taxid').text(datos.datoGrupo[0].taxi);
                $('#efectivod').text(datos.datoGrupo[0].efectivo);
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
            },
        );
        $('#exampleModal').modal('show');
    });

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
});
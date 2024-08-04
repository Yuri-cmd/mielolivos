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
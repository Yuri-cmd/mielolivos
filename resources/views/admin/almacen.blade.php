@extends('layout/admin-layout')
@section('content')
    <div class="contenedor-almacen">
        <div class="page-title-box">
            <div class="col-md-4">
                <h6 class="page-title">Productos</h6>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"
                            style="text-decoration: none; color: black;">Almacen</a></li>
                </ol>
            </div>
        </div>
        <div class="mt-10">
            <div class="card" style="width: 100%">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">Lista de Productos</h4>
                    </div>
                    <div>
                        <button data-bs-toggle="modal" data-bs-target="#importarModal" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel-fill"></i> Importar
                        </button>
                        <button data-bs-toggle="modal" data-bs-target="#modal-add-prod" class="btn btn-primary"
                            style="background-color: #626ed4; border-color: #626ed4;"><i class="bi bi-plus-lg"></i> Agregar
                            Producto
                        </button>
                        <button class="btn btn-danger btnBorrar"><i class="bi bi-trash-fill"></i> Borrar</button>
                    </div>
                </div>
                <div class="card-body" style="height: auto">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Precio 1</th>
                                <th class="text-center">Precio 2</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Proveedor</th>
                                <th class="text-center">Aumentar Stock</th>
                                <th class="text-center">Editar</th>
                                <th class="text-center">Eliminar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="actualizarStock" tabindex="-1" aria-labelledby="actualizarStockLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="actualizarStockLabel">Actualizar Stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="itemId" hidden>
                        <label for="stock" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="nstock">
                        <small class="form-text text-muted">La cantidad ingresada se sumara a la cantidad actual</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="updateStock">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editarProductoModalLabel">Editar Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarProductoForm">
                        <input type="text" class="form-control" id="editId" name="editId" hidden>
                        <div class="form-group">
                            <label for="editNombre">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" name="editNombre">
                        </div>
                        <div class="form-group">
                            <label for="editDescripcion">Descripción</label>
                            <input type="text" class="form-control" id="editDescripcion" name="editDescripcion">
                        </div>
                        <div class="form-group">
                            <label for="editPrecio1">Precio 1</label>
                            <input type="text" class="form-control" id="editPrecio1" name="editPrecio1">
                        </div>
                        <div class="form-group">
                            <label for="editPrecio2">Precio 2</label>
                            <input type="text" class="form-control" id="editPrecio2" name="editPrecio2">
                        </div>
                        <div class="form-group">
                            <label for="editStock">Stock</label>
                            <input type="number" class="form-control" id="editStock" name="editStock">
                        </div>
                        <div class="form-group">
                            <label for="editProveedor">Proveedor</label>
                            <input type="text" class="form-control" id="editProveedor" name="editProveedor">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarCambios">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar producto -->
    <div class="modal fade" id="modal-add-prod" tabindex="-1" aria-labelledby="modal-add-prodLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-add-prodLabel">Agregar Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarProducto">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="form-group">
                            <label for="precio1">Precio 1</label>
                            <input type="text" class="form-control" id="precio1" name="precio1" required>
                        </div>
                        <div class="form-group">
                            <label for="precio2">Precio 2</label>
                            <input type="text" class="form-control" id="precio2" name="precio2">
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="form-group">
                            <label for="proveedor">Proveedor</label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnAgregarProducto">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importarModal" tabindex="-1" aria-labelledby="importarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importarModalLabel">Importar Productos con EXCEL</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('importar.productos') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <p>Descargue el modelo en <span class="fw-bold">EXCEL</span> para importar, no modifique los
                                campos en el archivo, <span class="fw-bold">click para descargar</span> <a
                                    href="{{ route('exportar.productos') }}">plantilla.xlsx</a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Importar Excel:</label>
                            <input id="file-import-exel"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                type="file" name="file">
                        </div>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

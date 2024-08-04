<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\CobradorController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ProductoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/vendedor/dashboard', [VendedorController::class, 'index'])->name('vendedor.dashboard');
Route::get('/cobrador/dashboard', [CobradorController::class, 'index'])->name('cobrador.dashboard');

//Dashboard
Route::get('/admin/get-master', [AdminController::class, 'getMaster'])->name('getMaster');
Route::post('/admin/get-master-usuario', [AdminController::class, 'getMasterUsuario'])->name('getMasterUsuario');
Route::post('/admin/save-master', [AdminController::class, 'saveMaster'])->name('masterStock');
Route::post('/admin/get-master-producto', [ProductoController::class, 'getProductoMaster'])->name('getProductoMaster');

// Almancen
Route::get('/admin/almacen', [AdminController::class, 'almacen'])->name('almacen');
Route::get('/get-producto', [ProductoController::class, 'getProducto'])->name('getProducto');
Route::post('/update-estado-producto', [ProductoController::class, 'updateEstadoProducto'])->name('updateEstadoProducto');
Route::post('/update-stock-producto', [ProductoController::class, 'updateStockProducto'])->name('updateStockProducto');
Route::post('/actualizar-producto', [ProductoController::class, 'update'])->name('updateProducto');
Route::post('/crear-producto', [ProductoController::class, 'store'])->name('createProducto');
Route::get('/exportar-productos', [ExcelController::class, 'exportProductos'])->name('exportar.productos');
Route::post('/importar-productos', [ProductoController::class, 'importar'])->name('importar.productos');

//Usuarios
Route::post('/admin/asociados', [AdminController::class, 'createAsociado'])->name('createAsociado');
Route::post('/admin/delete/asociado', [AdminController::class, 'deleteAsociado'])->name('deleteAsociado');
Route::post('/admin/asociados', [AdminController::class, 'createAsociado'])->name('createAsociado');
Route::post('/admin/delete/asociado', [AdminController::class, 'deleteAsociado'])->name('deleteAsociado');
Route::post('/admin/cobradores', [AdminController::class, 'createCobrador'])->name('createCobrador');
Route::post('/admin/delete/cobrador', [AdminController::class, 'deleteCobrador'])->name('deleteCobrador');

//buscador 
Route::get('/admin/buscador', [AdminController::class, 'getVentas'])->name('getVentas');

//Grupos
Route::post('/admin/grupos', [AdminController::class, 'createGrupo'])->name('createGrupo');
Route::post('/admin/delete/grupo', [AdminController::class, 'deleteGrupo'])->name('deleteGrupo');
Route::post('/admin/detalle/grupo', [AdminController::class, 'createDetalleGrupo'])->name('createDetalleGrupo');
Route::post('/admin/save/grupo', [AdminController::class, 'saveDetalle'])->name('saveDetalle');
Route::post('/admin/get/grupo', [AdminController::class, 'getGrupoDetalle'])->name('getGrupoDetalle');
Route::post('/admin/save/deposito/taxi', [AdminController::class, 'updateDepositoTaxiGrupo'])->name('updateDepositoTaxiGrupo');
Route::post('/admin/save/campo/usuario', [AdminController::class, 'updateCampoUsuario'])->name('updateCampoUsuario');
Route::post('/admin/detalle/vendedor', [AdminController::class, 'getDetalleVendedor'])->name('getDetalleVendedor');
Route::post('/filtrar-grupos', [AdminController::class, 'filtrarGrupos'])->name('filtrarGrupos');
Route::post('/asignar-cobrador', [AdminController::class, 'asignarCobrador'])->name('asignarCobrador');

//Vendedor
Route::post('/vendedor/saveVenta', [VendedorController::class, 'saveVenta'])->name('saveVenta');
Route::get('/vendedor/venta/{id}', [VendedorController::class, 'seeVenta'])->name('seeVenta');

//Cobrador
Route::post('/cobrador/detalle/venta', [CobradorController::class, 'detalleVenta'])->name('detalleVenta');
Route::post('/cobrador/save/pago', [CobradorController::class, 'savePago'])->name('savePago');
Route::post('/cobrador/update/venta', [CobradorController::class, 'updateEstadoVenta'])->name('updateEstadoVenta');
Route::get('/ventas/filter', [CobradorController::class, 'filter'])->name('filter');

//Estadisticas
Route::get('/buscar-usuarios', [AdminController::class, 'buscarUsuarios'])->name('buscar.usuarios');

//Caja Chica
Route::post('saveCajaChica', [AdminController::class, 'saveCajaChica'])->name('saveCajaChica');
Route::get('getCajaChica', [AdminController::class, 'getCajaChica'])->name('getCajaChica');
Route::post('UpdateSaldoCajaChica', [AdminController::class, 'updateSaldoCajaChica'])->name('updateSaldoCajaChica');

//Descuentos
Route::get('getDescuentos', [AdminController::class, 'getDescuentos'])->name('getDescuentos');
Route::post('/update-descuento', [AdminController::class, 'updateDescuento'])->name('updateDescuento');

//Ventas Oficina
Route::get('getVentasOficina', [AdminController::class, 'getVentasOficina'])->name('getVentasOficina');
Route::post('saveVentasOficina', [AdminController::class, 'saveVentasOficina'])->name('saveVentasOficina');
Route::post('getVentasOficinaDetalle', [AdminController::class, 'getVentasOficinaDetalle'])->name('getVentasOficinaDetalle');

//Comision  
Route::get('getComision', [AdminController::class, 'getComision'])->name('getComision');
Route::post('updateComision', [AdminController::class, 'updateComision'])->name('updateComision');
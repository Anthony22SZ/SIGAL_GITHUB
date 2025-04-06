<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\MaterialesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\InventariosProductosController;
use App\Http\Controllers\FacturaComprasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContraseñaController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetalleFacturaComprasController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\CaiController;
use App\Http\Controllers\PuntosEmisionController;
use App\Http\Controllers\TiposDocumentosController;
use App\Http\Controllers\FacturasVentasController;
use App\Http\Controllers\ObjetosController;
use App\Http\Controllers\PermisosController;

// Ruta para mostrar el formulario de login (si usas una vista de login)
Route::get('/', function () {
    return view('Login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperar la contraseña
Route::post('/solicitar-recuperacion', [ContraseñaController::class, 'solicitarRecuperacion']);
Route::post('/verificar-codigo', [ContraseñaController::class, 'verificarCodigo']);
Route::post('/cambiar-contrasena', [ContraseñaController::class, 'cambiarContrasena']);

// Ruta para mostrar el formulario de recuperación de contraseña
Route::get('/SolicitarRecuperacion', function () {
    return view('solicitarRecuperacion');
});

// Ruta para mostrar el formulario de verificación de código
Route::get('/verificar-codigo', function () {
    return view('VerificacionCodigo');
});

// Ruta para mostrar el formulario de cambio de contraseña
Route::get('/cambiar-contrasena', function () {
    return view('CambiarContrasena');
});


// Ruta para el dashboard, que solo debería ser accesible si el usuario está autenticado
Route::get('/dashboard', function () {
    return view('Dashboard');
})->name('dashboard');

// Rutas para Clientes

Route::get('/clientes', [ClientesController::class, 'listarCliente'])->name('clientes.listar');
Route::get('/clientes/crear', [ClientesController::class, 'crearCliente'])->name('clientes.crear');
Route::post('/clientes', [ClientesController::class, 'insertarCliente'])->name('clientes.insertar');
Route::get('/actualizar-cliente/{COD_CLIENTE}', [ClientesController::class, 'mostrarActualizarCliente'])->name('clientes.mostrar-actualizar');
Route::put('/clientes/actualizar/{COD_CLIENTE}', [ClientesController::class, 'actualizarCliente'])->name('clientes.actualizar');
Route::delete('/clientes/{COD_CLIENTE}', [ClientesController::class, 'eliminarCliente'])->name('clientes.eliminar');

// Ruta para procesar la actualización de un cliente (PUT)
Route::put('/clientes/actualizar/{COD_CLIENTE}', [ClientesController::class, 'actualizarCliente'])->name('clientes.actualizar');

 // Rutas para Empleados
 Route::post('/empleados', [EmpleadosController::class, 'insertarEmpleado'])->name('empleados.insertar');
 Route::get('/empleados', [EmpleadosController::class, 'listarEmpleados'])->name('empleados.listar');
 Route::get('/empleados/crear', [EmpleadosController::class, 'crearEmpleado'])->name('empleados.crear');
 Route::get('/actualizar-empleado/{COD_EMPLEADO}', [EmpleadosController::class, 'mostrarActualizarEmpleado'])->name('empleados.mostrar-actualizar');
 Route::put('/empleados/actualizar/{COD_EMPLEADO}', [EmpleadosController::class, 'actualizarEmpleado'])->name('empleados.actualizar');
 Route::delete('/empleados/{COD_EMPLEADO}', [EmpleadosController::class, 'eliminarEmpleado'])->name('empleados.eliminar');

 // Rutas para Proveedores
 Route::get('/proveedores/crear', [ProveedoresController::class, 'crearProveedor'])->name('proveedores.crear');
 Route::post('/proveedores', [ProveedoresController::class, 'insertarProveedor'])->name('proveedores.insertar');
 Route::get('/proveedores', [ProveedoresController::class, 'listarProveedores'])->name('proveedores.listar');
 Route::delete('/proveedores/{COD_PROVEEDOR}', [ProveedoresController::class, 'eliminarProveedor'])->name('proveedores.eliminar');
 Route::get('/actualizar-proveedor/{COD_PROVEEDOR}', [ProveedoresController::class, 'mostrarActualizarProveedor'])->name('proveedores.mostrar-actualizar');
 Route::put('/proveedores/actualizar/{COD_PROVEEDOR}', [ProveedoresController::class, 'actualizarProveedor'])->name('proveedores.actualizar');
 
 // Rutass para Sucursales
 Route::get('/sucursales', [SucursalesController::class, 'index'])->name('sucursales.index');
 Route::get('/sucursales/crear', [SucursalesController::class, 'create'])->name('sucursales.create');
 Route::post('/sucursales', [SucursalesController::class, 'store'])->name('sucursales.store');
 Route::get('/{cod_sucursal}/edit', [SucursalesController::class, 'edit'])->name('sucursales.edit');
 Route::put('/{cod_sucursal}', [SucursalesController::class, 'update'])->name('sucursales.update');
 Route::put('/sucursales/{cod_sucursal}/estado', [SucursalesController::class, 'toggleState'])->name('sucursales.toggleState');

 // Rutas para Materiales
Route::get('/materiales', [MaterialesController::class, 'listarMateriales'])->name('materiales.listar');
Route::post('/materiales', [MaterialesController::class, 'insertarMaterial'])->name('materiales.insertar');
Route::get('/materiales/crear', [MaterialesController::class, 'crearMaterial'])->name('materiales.crear');
Route::delete('/eliminar-material/{codigo}', [MaterialesController::class, 'eliminarMaterial']);


 // Rutas para Productos
Route::post('/productos', [ProductosController::class, 'insertarProducto'])->name('productos.insertar');
Route::get('/productos/crear', [ProductosController::class, 'mostrarCrearProducto'])->name('productos.crear');
Route::get('/productos', [ProductosController::class, 'listarProductos'])->name('productos.listar');
Route::get('/productos/{COD_PRODUCTO}/editar', [ProductosController::class, 'mostrarActualizarProducto'])->name('productos.editar');
Route::put('/productos/{COD_PRODUCTO}', [ProductosController::class, 'actualizarProducto'])->name('productos.actualizar');
Route::delete('/productos/{COD_PRODUCTO}', [ProductosController::class, 'eliminarProducto'])->name('productos.eliminar');

 // Rutas para categorias
Route::get('/categorias', [CategoriasController::class, 'listarCategoria'])->name('categorias.listar');
Route::get('/categorias/crear', [CategoriasController::class, 'crearCategoria'])->name('categorias.crear');
Route::post('/categorias', [CategoriasController::class, 'insertarCategoria'])->name('categorias.insertar');
Route::get('/categorias/{COD_CATEGORIA}/editar', [CategoriasController::class, 'mostrarActualizarCategoria'])->name('categorias.editar');
Route::put('/categorias/{COD_CATEGORIA}', [CategoriasController::class, 'actualizarCategoria'])->name('categorias.actualizar');
Route::delete('/categorias/{COD_CATEGORIA}', [CategoriasController::class, 'eliminarCategoria'])->name('categorias.eliminar');


 // Rutas para Inventarios
 Route::get('/inventario/materiales', [InventarioController::class, 'listarInventario'])->name('inventario.materiales');
 Route::post('/salida-material', [InventarioController::class, 'registrarSalida'])->name('salida-material');
 

 // rutas inventarios productos 
 Route::get('/inventario', [InventariosProductosController::class, 'listarInventarioProducto'])->name('inventario.listar');
 Route::get('/inventario/productos/crear', [InventariosProductosController::class, 'mostrarCrearProductoInventario'])->name('inventario.productos.crear');
 Route::post('/inventario/productos', [InventariosProductosController::class, 'insertarProductoInventario'])->name('inventario.productos.insertar');
 Route::get('/inventario/productos/editar/{codigo}/{cod_sucursal}', [InventariosProductosController::class, 'mostrarActualizarInventarioProducto'])->name('inventario.productos.editar');
 Route::put('/inventario/productos', [InventariosProductosController::class, 'actualizarInventarioProducto'])->name('inventario.productos.actualizar');
 Route::put('/inventario/productos/agregar-stock', [InventariosProductosController::class, 'agregarStockProducto'])->name('inventario.productos.agregar-stock');

Route::middleware('jwt')->group(function () {
    Route::post('/inventario/traslados', [InventariosProductosController::class, 'realizarTraslado'])->name('inventario.traslados.realizar');
    Route::get('/traslados', [InventariosProductosController::class, 'mostrarFormularioTraslado'])->name('traslados.listar');
    Route::get('/traslados/historial', [InventariosProductosController::class, 'mostrarHistorialTraslados'])->name('traslados.historial');
});
 // Rutas para Compras

 Route::get('/crear-factura-compra', [FacturaComprasController::class, 'CrearFacturaCompra'])->name('FacturaCompra.crear');
// Ruta POST para procesar el formulario
Route::post('/facturas-compra-completa', [FacturaComprasController::class, 'insertarFacturaCompraCompleta'])->name('FacturaCompra.insertar');
Route::get('/facturas', [FacturaComprasController::class, 'listarFacturas'])->name('FacturaCompra.listar');
Route::get('/facturas/detalle/{numero_factura}', [FacturaComprasController::class, 'verDetalleFactura'])->name('FacturaCompra.detalle');
 // Rutas para Seguridad
 Route::get('/usuarios/crear', [UsuariosController::class, 'crearUsuario'])->name('usuarios.crear');
 Route::post('/usuarios', [UsuariosController::class, 'insertarUsuario'])->name('usuarios.insertar');
 Route::get('/usuarios', [UsuariosController::class, 'listarUsuarios'])->name('usuarios.listar');

 
 Route::middleware('jwt')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/usuarios', [UsuariosController::class, 'listarUsuarios'])->name('usuarios.listar');

    // Rutas para Roles
    Route::get('/roles', [RolesController::class, 'listarRoles'])->name('roles.listar');
    Route::get('/roles/crear', [RolesController::class, 'crearRol'])->name('roles.crear');
    Route::post('/roles/insertar', [RolesController::class, 'insertarRol'])->name('roles.insertar');
});



 // Rutas para Perfil
 Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
 Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

 Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

 Route::get('/empresa', [EmpresasController::class, 'mostrarDatosEmpresa'])->name('empresa.mostrar');
 Route::put('/empresa/{cod_empresa}', [EmpresasController::class, 'actualizarDatosEmpresa'])->name('empresa.actualizar');

// Rutas para CAIs
Route::get('/cai', [CaiController::class, 'listar'])->name('cai.listar');
Route::post('/cai', [CaiController::class, 'crear'])->name('cai.crear');

Route::get('/puntos-emision', [PuntosEmisionController::class, 'listar'])->name('puntos-emision.listar');
Route::get('/puntos-emision/crear', [PuntosEmisionController::class, 'mostrarFormularioCrear'])->name('puntos-emision.crear.form');
Route::post('/puntos-emision', [PuntosEmisionController::class, 'crear'])->name('puntos-emision.crear');
Route::get('/puntos-emision/{cod_punto_emision}/editar', [PuntosEmisionController::class, 'mostrarFormularioEditar'])->name('puntos-emision.editar.form');
Route::put('/puntos-emision/{cod_punto_emision}', [PuntosEmisionController::class, 'actualizar'])->name('puntos-emision.actualizar');

// Rutas para Tipos de Documento
Route::get('/tipos-documento', [TiposDocumentosController::class, 'listar'])->name('tipos-documento.listar');
Route::get('/tipos-documento/crear', [TiposDocumentosController::class, 'mostrarFormularioCrear'])->name('tipos-documento.crear.form');
Route::post('/tipos-documento', [TiposDocumentosController::class, 'crear'])->name('tipos-documento.crear');




/*
    Route::get('/Facturas', [FacturasVentasController::class, 'index'])->name('index');
    Route::get('/Facturas/crear', [FacturasVentasController::class, 'create'])->name('FacturasVenta');
    Route::post('/venta', [FacturasVentasController::class, 'crearFacturaVenta'])->name('store');
    Route::get('/{cod_factura}', [FacturasVentasController::class, 'show'])->name('show');
    Route::post('/venta/{cod_factura}/productos', [FacturasVentasController::class, 'agregarProductoFactura'])->name('add-product');
    Route::put('/venta/{cod_factura}/finalizar', [FacturasVentasController::class, 'finalizarFactura'])->name('finalize');

    Route::get('/facturas/datos', [FacturasVentasController::class, 'getDatosFacturacion'])->name('facturas.datos');

Route::get('/facturas/clientes', [FacturasVentasController::class, 'getClientes'])->name('facturas.clientes');
*/

Route::middleware('jwt')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/usuarios', [UsuariosController::class, 'listarUsuarios'])->name('usuarios.listar');
    Route::get('/usuarios/crear', [UsuariosController::class, 'crearUsuario'])->name('usuarios.crear');
    Route::post('/usuarios', [UsuariosController::class, 'insertarUsuario'])->name('usuarios.insertar');
    Route::get('/clientes', [ClientesController::class, 'listarCliente'])->name('clientes.listar');
    Route::get('/clientes/crear', [ClientesController::class, 'crearCliente'])->name('clientes.crear');
    Route::post('/clientes', [ClientesController::class, 'insertarCliente'])->name('clientes.insertar');
    Route::get('/empleados', [EmpleadosController::class, 'listarEmpleados'])->name('empleados.listar');
    Route::get('/empleados/crear', [EmpleadosController::class, 'crearEmpleado'])->name('empleados.crear');
    Route::post('/empleados', [EmpleadosController::class, 'insertarEmpleado'])->name('empleados.insertar');
    Route::get('/proveedores', [ProveedoresController::class, 'listarProveedores'])->name('proveedores.listar');
    Route::get('/proveedores/crear', [ProveedoresController::class, 'crearProveedor'])->name('proveedores.crear');
    Route::post('/proveedores', [ProveedoresController::class, 'insertarProveedor'])->name('proveedores.insertar');
    Route::get('/materiales', [MaterialesController::class, 'listarMateriales'])->name('materiales.listar');
    Route::get('/materiales/crear', [MaterialesController::class, 'crearMaterial'])->name('materiales.crear');
    Route::post('/materiales', [MaterialesController::class, 'insertarMaterial'])->name('materiales.insertar');
    Route::get('/roles', [RolesController::class, 'listarRoles'])->name('roles.listar');
    Route::get('/roles/crear', [RolesController::class, 'crearRol'])->name('roles.crear');
    Route::post('/roles/insertar', [RolesController::class, 'insertarRol'])->name('roles.insertar');
Route::get('/objetos', [ObjetosController::class, 'listarObjetos'])->name('objetos.listar');
Route::get('/objetos/crear', [ObjetosController::class, 'crear'])->name('objetos.crear');
Route::post('/objetos/insertar', [ObjetosController::class, 'insertar'])->name('objetos.insertar');

Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos.index');
Route::post('/permisos/guardar', [PermisosController::class, 'guardar'])->name('permisos.guardar');
});
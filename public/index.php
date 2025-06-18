<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\UsuariosController;
use Controllers\AplicacionController;
use Controllers\PermisosController;
use Controllers\AsignacionPermisosController;
use Controllers\ComisionController;
use Controllers\ComisionPersonalController;
use Controllers\EstadisticasController;
use Controllers\HistorialActController;
use Controllers\MapasController;

$router = new Router();
$router->setBaseURL('/contreras_final_comisiones_ingsoft1');

// RUTA PRINCIPAL - Login/Dashboard
$router->get('/', [LoginController::class,'renderizarPagina']);
$router->get('/login', [LoginController::class,'renderizarPagina']);
$router->get('/inicio', [AppController::class,'index']);
$router->post('/API/login', [LoginController::class,'login']);
$router->get('/logout', [LoginController::class,'logout']);

// USUARIOS
$router->get('/usuarios', [UsuariosController::class, 'renderizarPagina']);
$router->post('/usuarios/guardarAPI', [UsuariosController::class, 'guardarAPI']);
$router->get('/usuarios/buscarAPI', [UsuariosController::class, 'buscarAPI']);
$router->post('/usuarios/modificarAPI', [UsuariosController::class, 'modificarAPI']);
$router->get('/usuarios/eliminar', [UsuariosController::class, 'EliminarAPI']);

// APLICACIONES
$router->get('/aplicacion', [AplicacionController::class, 'renderizarPagina']);
$router->post('/aplicacion/guardarAPI', [AplicacionController::class, 'guardarAPI']);
$router->get('/aplicacion/buscarAPI', [AplicacionController::class, 'buscarAPI']);
$router->post('/aplicacion/modificarAPI', [AplicacionController::class, 'modificarAPI']);
$router->get('/aplicacion/eliminar', [AplicacionController::class, 'EliminarAPI']);

// PERMISOS
$router->get('/permisos', [PermisosController::class, 'renderizarPagina']);
$router->post('/permisos/guardarAPI', [PermisosController::class, 'guardarAPI']);
$router->get('/permisos/buscarAPI', [PermisosController::class, 'buscarAPI']);
$router->post('/permisos/modificarAPI', [PermisosController::class, 'modificarAPI']);
$router->get('/permisos/eliminar', [PermisosController::class, 'EliminarAPI']);
$router->get('/permisos/buscarUsuariosAPI', [PermisosController::class, 'buscarUsuariosAPI']);
$router->get('/permisos/buscarAplicacionesAPI', [PermisosController::class, 'buscarAplicacionesAPI']);

// ASIGNACIÓN DE PERMISOS
$router->get('/asignacionpermisos', [AsignacionPermisosController::class, 'renderizarPagina']);
$router->post('/asignacionpermisos/guardarAPI', [AsignacionPermisosController::class, 'guardarAPI']);
$router->get('/asignacionpermisos/buscarAPI', [AsignacionPermisosController::class, 'buscarAPI']);
$router->post('/asignacionpermisos/modificarAPI', [AsignacionPermisosController::class, 'modificarAPI']);
$router->get('/asignacionpermisos/eliminar', [AsignacionPermisosController::class, 'EliminarAPI']);
$router->get('/asignacionpermisos/buscarUsuariosAPI', [AsignacionPermisosController::class, 'buscarUsuariosAPI']);
$router->get('/asignacionpermisos/buscarAplicacionesAPI', [AsignacionPermisosController::class, 'buscarAplicacionesAPI']);
$router->get('/asignacionpermisos/buscarPermisosAPI', [AsignacionPermisosController::class, 'buscarPermisosAPI']);

// COMISIONES
$router->get('/comisiones', [ComisionController::class, 'renderizarPagina']);
$router->post('/comisiones/guardarAPI', [ComisionController::class, 'guardarAPI']);
$router->get('/comisiones/buscarAPI', [ComisionController::class, 'buscarAPI']);
$router->post('/comisiones/modificarAPI', [ComisionController::class, 'modificarAPI']);
$router->get('/comisiones/eliminar', [ComisionController::class, 'EliminarAPI']);

// COMISIÓN PERSONAL
$router->get('/comisionpersonal', [ComisionPersonalController::class, 'renderizarPagina']);
$router->post('/comisionpersonal/guardarAPI', [ComisionPersonalController::class, 'guardarAPI']);
$router->get('/comisionpersonal/buscarAPI', [ComisionPersonalController::class, 'buscarAPI']);
$router->post('/comisionpersonal/modificarAPI', [ComisionPersonalController::class, 'modificarAPI']);
$router->get('/comisionpersonal/eliminar', [ComisionPersonalController::class, 'EliminarAPI']);
$router->get('/comisionpersonal/buscarComisionesAPI', [ComisionPersonalController::class, 'buscarComisionesAPI']);
$router->get('/comisionpersonal/buscarUsuariosAPI', [ComisionPersonalController::class, 'buscarUsuariosAPI']);
$router->get('/comisionpersonal/validarAsignacionAPI', [ComisionPersonalController::class, 'validarAsignacionAPI']);

// ESTADÍSTICAS
$router->get('/estadisticas', [EstadisticasController::class, 'renderizarPagina']);
$router->get('/estadisticas/buscarComisionesPorTipoAPI', [EstadisticasController::class, 'buscarComisionesPorTipoAPI']);
$router->get('/estadisticas/buscarComisionesPorEstadoAPI', [EstadisticasController::class, 'buscarComisionesPorEstadoAPI']);
$router->get('/estadisticas/buscarPersonalMasActivoAPI', [EstadisticasController::class, 'buscarPersonalMasActivoAPI']);
$router->get('/estadisticas/buscarComisionesPorDuracionAPI', [EstadisticasController::class, 'buscarComisionesPorDuracionAPI']);
$router->get('/estadisticas/buscarComisionesMensualesAPI', [EstadisticasController::class, 'buscarComisionesMensualesAPI']);
$router->get('/estadisticas/buscarUsuariosPorPermisosAPI', [EstadisticasController::class, 'buscarUsuariosPorPermisosAPI']);

// MAPA
$router->get('/mapa', [MapasController::class, 'renderizarPagina']);

// HISTORIAL DE ACTIVIDADES
$router->get('/historial', [HistorialActController::class, 'renderizarPagina']);
$router->get('/historial/buscarAPI', [HistorialActController::class, 'buscarAPI']);
$router->get('/historial/buscarUsuariosAPI', [HistorialActController::class, 'buscarUsuariosAPI']);
$router->get('/historial/exportarReporteAPI', [HistorialActController::class, 'exportarReporteAPI']);
$router->get('/historial/buscarActividadPorTipoAPI', [HistorialActController::class, 'buscarActividadPorTipoAPI']);
$router->get('/historial/buscarActividadPorUsuarioAPI', [HistorialActController::class, 'buscarActividadPorUsuarioAPI']);
$router->get('/historial/buscarActividadPorDiaAPI', [HistorialActController::class, 'buscarActividadPorDiaAPI']);
$router->get('/historial/buscarActividadPorModuloAPI', [HistorialActController::class, 'buscarActividadPorModuloAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
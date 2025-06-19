<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\AsignacionPermisos;

class AsignacionPermisosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('asignacionpermisos/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['asignacion_usuario_id'] = filter_var($_POST['asignacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_usuario_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario válido'
                ]);
                exit;
            }

            $_POST['asignacion_app_id'] = filter_var($_POST['asignacion_app_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_app_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación válida'
                ]);
                exit;
            }

            $_POST['asignacion_permiso_id'] = filter_var($_POST['asignacion_permiso_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_permiso_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un permiso válido'
                ]);
                exit;
            }

            $_POST['asignacion_usuario_asigno'] = filter_var($_POST['asignacion_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['asignacion_usuario_asigno'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar quién asigna el permiso'
                ]);
                exit;
            }

            $_POST['asignacion_motivo'] = trim(htmlspecialchars($_POST['asignacion_motivo']));
            
            $cantidad_motivo = strlen($_POST['asignacion_motivo']);
            
            if ($cantidad_motivo < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo debe tener más de 4 caracteres'
                ]);
                exit;
            }

            $verificarPermisoExiste = self::fetchArray("SELECT permiso_id FROM macs_permiso WHERE permiso_id = {$_POST['asignacion_permiso_id']} AND app_id = {$_POST['asignacion_app_id']} AND permiso_situacion = 1");

            if (count($verificarPermisoExiste) == 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El permiso seleccionado no pertenece a la aplicación elegida'
                ]);
                exit;
            }

            $verificarDuplicado = self::fetchArray("SELECT asignacion_id FROM macs_asig_permisos WHERE asignacion_usuario_id = {$_POST['asignacion_usuario_id']} AND asignacion_permiso_id = {$_POST['asignacion_permiso_id']} AND asignacion_situacion = 1");

            if (count($verificarDuplicado) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este permiso ya está asignado al usuario seleccionado'
                ]);
                exit;
            }

            $_POST['asignacion_fecha'] = '';
            $_POST['asignacion_quitar_fechaPermiso'] = null;
            
            $asignacion = new AsignacionPermisos($_POST);
            $resultado = $asignacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso asignado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al asignar el permiso',
                ]);
                exit;
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        try {
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;
            $permiso_id = isset($_GET['permiso_id']) ? $_GET['permiso_id'] : null;

            $condiciones = ["ap.asignacion_situacion = 1"];

            if ($usuario_id) {
                $condiciones[] = "ap.asignacion_usuario_id = {$usuario_id}";
            }

            if ($app_id) {
                $condiciones[] = "ap.asignacion_app_id = {$app_id}";
            }

            if ($permiso_id) {
                $condiciones[] = "ap.asignacion_permiso_id = {$permiso_id}";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT 
                        ap.*,
                        u.usuario_nom1,
                        u.usuario_ape1,
                        a.app_nombre_corto,
                        p.permiso_nombre,
                        p.permiso_clave,
                        ua.usuario_nom1 as asigno_nom1,
                        ua.usuario_ape1 as asigno_ape1
                    FROM macs_asig_permisos ap 
                    INNER JOIN macs_usuario u ON ap.asignacion_usuario_id = u.usuario_id
                    INNER JOIN macs_aplicacion a ON ap.asignacion_app_id = a.app_id 
                    INNER JOIN macs_permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    INNER JOIN macs_usuario ua ON ap.asignacion_usuario_asigno = ua.usuario_id
                    WHERE $where 
                    ORDER BY ap.asignacion_fecha DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asignaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['asignacion_id'];
        
        $_POST['asignacion_usuario_id'] = filter_var($_POST['asignacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['asignacion_usuario_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario válido'
            ]);
            return;
        }

        $_POST['asignacion_app_id'] = filter_var($_POST['asignacion_app_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['asignacion_app_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación válida'
            ]);
            return;
        }

        $_POST['asignacion_permiso_id'] = filter_var($_POST['asignacion_permiso_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['asignacion_permiso_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un permiso válido'
            ]);
            return;
        }

        $_POST['asignacion_usuario_asigno'] = filter_var($_POST['asignacion_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['asignacion_usuario_asigno'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario válido'
            ]);
            return;
        }

        $_POST['asignacion_motivo'] = trim(htmlspecialchars($_POST['asignacion_motivo']));
        
        $cantidad_motivo = strlen($_POST['asignacion_motivo']);
        
        if ($cantidad_motivo < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe tener más de 4 caracteres'
            ]);
            return;
        }

        try {
            $verificarPermisoExiste = self::fetchArray("SELECT permiso_id FROM macs_permiso WHERE permiso_id = {$_POST['asignacion_permiso_id']} AND app_id = {$_POST['asignacion_app_id']} AND permiso_situacion = 1");

            if (count($verificarPermisoExiste) == 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El permiso seleccionado no pertenece a la aplicación elegida'
                ]);
                return;
            }

            $verificarDuplicado = self::fetchArray("SELECT asignacion_id FROM macs_asig_permisos WHERE asignacion_usuario_id = {$_POST['asignacion_usuario_id']} AND asignacion_permiso_id = {$_POST['asignacion_permiso_id']} AND asignacion_situacion = 1 AND asignacion_id != {$id}");

            if (count($verificarDuplicado) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este permiso ya está asignado al usuario seleccionado'
                ]);
                return;
            }

            $data = AsignacionPermisos::find($id);
            $data->sincronizar([
                'asignacion_usuario_id' => $_POST['asignacion_usuario_id'],
                'asignacion_app_id' => $_POST['asignacion_app_id'],
                'asignacion_permiso_id' => $_POST['asignacion_permiso_id'],
                'asignacion_usuario_asigno' => $_POST['asignacion_usuario_asigno'],
                'asignacion_motivo' => $_POST['asignacion_motivo'],
                'asignacion_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La asignación ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $ejecutar = AsignacionPermisos::EliminarAsignacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la asignación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuariosAPI()
    {
        try {
            $sql = "SELECT usuario_id, usuario_nom1, usuario_ape1 
                    FROM macs_usuario 
                    WHERE usuario_situacion = 1 
                    ORDER BY usuario_nom1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAplicacionesAPI()
    {
        try {
            $sql = "SELECT app_id, app_nombre_corto FROM macs_aplicacion WHERE app_situacion = 1 ORDER BY app_nombre_corto";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las aplicaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPermisosAPI()
    {
        try {
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;

            if ($app_id) {
                $sql = "SELECT permiso_id, permiso_nombre, permiso_clave, app_id
                        FROM macs_permiso 
                        WHERE app_id = {$app_id} AND permiso_situacion = 1 
                        ORDER BY permiso_nombre";
            } else {
                $sql = "SELECT permiso_id, permiso_nombre, permiso_clave, app_id
                        FROM macs_permiso 
                        WHERE permiso_situacion = 1 
                        ORDER BY permiso_nombre";
            }
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPermisosUsuarioAPI()
    {
        try {
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;

            if (!$usuario_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar el ID del usuario'
                ]);
                return;
            }

            $sql = "SELECT 
                        p.permiso_id,
                        p.permiso_nombre,
                        p.permiso_clave,
                        a.app_nombre_corto,
                        ap.asignacion_fecha
                    FROM macs_asig_permisos ap
                    INNER JOIN macs_permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    INNER JOIN macs_aplicacion a ON ap.asignacion_app_id = a.app_id
                    WHERE ap.asignacion_usuario_id = {$usuario_id}
                    AND ap.asignacion_situacion = 1
                    AND p.permiso_situacion = 1
                    ORDER BY a.app_nombre_corto, p.permiso_nombre";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos del usuario obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos del usuario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
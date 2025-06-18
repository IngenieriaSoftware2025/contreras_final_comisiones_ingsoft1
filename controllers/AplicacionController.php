<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;

class AplicacionController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('aplicacion/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));
            
            $cantidad_largo = strlen($_POST['app_nombre_largo']);
            
            if ($cantidad_largo < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre largo debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_largo > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre largo no puede exceder los 250 caracteres'
                ]);
                exit;
            }
            
            $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));
            
            $cantidad_medium = strlen($_POST['app_nombre_medium']);
            
            if ($cantidad_medium < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre mediano debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_medium > 150) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre mediano no puede exceder los 150 caracteres'
                ]);
                exit;
            }
            
            $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));
            $cantidad_corto = strlen($_POST['app_nombre_corto']);
            
            if ($cantidad_corto < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre corto debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_corto > 50) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre corto no puede exceder los 50 caracteres'
                ]);
                exit;
            }

            $verificarNombreCortoExistente = self::fetchArray("SELECT app_id FROM macs_aplicacion WHERE app_nombre_corto = '{$_POST['app_nombre_corto']}' AND app_situacion = 1");

            if (count($verificarNombreCortoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una aplicación con este nombre corto'
                ]);
                exit;
            }

            $verificarNombreLargoExistente = self::fetchArray("SELECT app_id FROM macs_aplicacion WHERE app_nombre_largo = '{$_POST['app_nombre_largo']}' AND app_situacion = 1");

            if (count($verificarNombreLargoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una aplicación con este nombre largo'
                ]);
                exit;
            }
            
            $_POST['app_fecha_creacion'] = '';
            $_POST['app_situacion'] = 1;
            
            $aplicacion = new Aplicacion($_POST);
            $resultado = $aplicacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicacion registrada correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar la aplicacion',
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
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : null;

            $condiciones = ["app_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "app_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "app_fecha_creacion <= '{$fecha_fin}'";
            }

            if ($nombre) {
                $condiciones[] = "(app_nombre_largo LIKE '%{$nombre}%' OR app_nombre_medium LIKE '%{$nombre}%' OR app_nombre_corto LIKE '%{$nombre}%')";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM macs_aplicacion WHERE $where ORDER BY app_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['app_id'];
        $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));

        $cantidad_largo = strlen($_POST['app_nombre_largo']);

        if ($cantidad_largo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre largo debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_largo > 250) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre largo no puede exceder los 250 caracteres'
            ]);
            return;
        }

        $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));

        $cantidad_medium = strlen($_POST['app_nombre_medium']);

        if ($cantidad_medium < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre mediano debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_medium > 150) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre mediano no puede exceder los 150 caracteres'
            ]);
            return;
        }

        $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));
        $cantidad_corto = strlen($_POST['app_nombre_corto']);

        if ($cantidad_corto < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre corto debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        if ($cantidad_corto > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre corto no puede exceder los 50 caracteres'
            ]);
            return;
        }

        try {
            $verificarNombreCortoExistente = self::fetchArray("SELECT app_id FROM macs_aplicacion WHERE app_nombre_corto = '{$_POST['app_nombre_corto']}' AND app_situacion = 1 AND app_id != {$id}");

            if (count($verificarNombreCortoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otra aplicación con este nombre corto'
                ]);
                return;
            }

            $verificarNombreLargoExistente = self::fetchArray("SELECT app_id FROM macs_aplicacion WHERE app_nombre_largo = '{$_POST['app_nombre_largo']}' AND app_situacion = 1 AND app_id != {$id}");

            if (count($verificarNombreLargoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otra aplicación con este nombre largo'
                ]);
                return;
            }

            $data = Aplicacion::find($id);
            $data->sincronizar([
                'app_nombre_largo' => $_POST['app_nombre_largo'],
                'app_nombre_medium' => $_POST['app_nombre_medium'],
                'app_nombre_corto' => $_POST['app_nombre_corto'],
                'app_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion de la aplicacion ha sido modificada exitosamente'
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

            $verificarPermisos = self::fetchArray("SELECT permiso_id FROM macs_permiso WHERE app_id = {$id} AND permiso_situacion = 1");

            if (count($verificarPermisos) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar la aplicación porque tiene permisos asociados'
                ]);
                return;
            }

            $verificarAsignaciones = self::fetchArray("SELECT asignacion_id FROM macs_asig_permisos WHERE asignacion_app_id = {$id} AND asignacion_situacion = 1");

            if (count($verificarAsignaciones) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar la aplicación porque tiene asignaciones de permisos activas'
                ]);
                return;
            }

            $ejecutar = Aplicacion::EliminarAplicaciones($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarEstadisticasAPI()
    {
        try {
            $sql = "SELECT 
                        a.app_id,
                        a.app_nombre_corto,
                        a.app_nombre_largo,
                        COUNT(DISTINCT p.permiso_id) as total_permisos,
                        COUNT(DISTINCT ap.asignacion_id) as total_asignaciones,
                        COUNT(DISTINCT ap.asignacion_usuario_id) as usuarios_con_permisos
                    FROM macs_aplicacion a
                    LEFT JOIN macs_permiso p ON a.app_id = p.app_id AND p.permiso_situacion = 1
                    LEFT JOIN macs_asig_permisos ap ON a.app_id = ap.asignacion_app_id AND ap.asignacion_situacion = 1
                    WHERE a.app_situacion = 1
                    GROUP BY a.app_id, a.app_nombre_corto, a.app_nombre_largo
                    ORDER BY total_asignaciones DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas de aplicaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las estadísticas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPermisosPorAplicacionAPI()
    {
        try {
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;

            if (!$app_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar el ID de la aplicación'
                ]);
                return;
            }

            $sql = "SELECT 
                        p.permiso_id,
                        p.permiso_nombre,
                        p.permiso_clave,
                        p.permiso_tipo,
                        p.permiso_desc,
                        COUNT(ap.asignacion_id) as veces_asignado
                    FROM macs_permiso p
                    LEFT JOIN macs_asig_permisos ap ON p.permiso_id = ap.asignacion_permiso_id AND ap.asignacion_situacion = 1
                    WHERE p.app_id = {$app_id} AND p.permiso_situacion = 1
                    GROUP BY p.permiso_id, p.permiso_nombre, p.permiso_clave, p.permiso_tipo, p.permiso_desc
                    ORDER BY veces_asignado DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos de la aplicación obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos de la aplicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuariosPorAplicacionAPI()
    {
        try {
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;

            if (!$app_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar el ID de la aplicación'
                ]);
                return;
            }

            $sql = "SELECT DISTINCT
                        u.usuario_id,
                        u.usuario_nom1,
                        u.usuario_ape1,
                        u.usuario_correo,
                        COUNT(ap.asignacion_id) as permisos_asignados
                    FROM macs_usuario u
                    INNER JOIN macs_asig_permisos ap ON u.usuario_id = ap.asignacion_usuario_id
                    WHERE ap.asignacion_app_id = {$app_id} 
                    AND ap.asignacion_situacion = 1 
                    AND u.usuario_situacion = 1
                    GROUP BY u.usuario_id, u.usuario_nom1, u.usuario_ape1, u.usuario_correo
                    ORDER BY permisos_asignados DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios de la aplicación obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios de la aplicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
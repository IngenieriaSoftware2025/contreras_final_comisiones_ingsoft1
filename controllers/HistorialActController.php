<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\HistorialAct;

class HistorialActController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        session_start();
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            header('Location: /contreras_final_comisiones_ingsoft1/inicio');
            exit;
        }
        
        $router->render('historial/index', []);
    }

    public static function registrarActividad($modulo, $accion, $descripcion, $ruta = '')
    {
        try {
            session_start();
            if(isset($_SESSION['usuario_id'])) {
                $historial_actividad = new HistorialAct([
                    'historial_usuario_id' => $_SESSION['usuario_id'],
                    'historial_usuario_nombre' => $_SESSION['user'],
                    'historial_modulo' => $modulo,
                    'historial_accion' => $accion,
                    'historial_descripcion' => $descripcion,
                    'historial_ip' => $_SERVER['REMOTE_ADDR'] ?? 'No disponible',
                    'historial_ruta' => $ruta,
                    'historial_situacion' => 1
                ]);
                $historial_actividad->crear();
            }
        } catch (Exception $e) {
            // Silenciar errores para no interrumpir el flujo
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
            $modulo = isset($_GET['modulo']) ? $_GET['modulo'] : null;
            $accion = isset($_GET['accion']) ? $_GET['accion'] : null;

            $condiciones = ["historial_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "historial_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "historial_fecha_creacion <= '{$fecha_fin}'";
            }

            if ($usuario_id) {
                $condiciones[] = "historial_usuario_id = {$usuario_id}";
            }

            if ($modulo) {
                $condiciones[] = "historial_modulo = '{$modulo}'";
            }

            if ($accion) {
                $condiciones[] = "historial_accion = '{$accion}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM macs_historial_act WHERE $where ORDER BY historial_fecha_creacion DESC, historial_id DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Actividades obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las actividades',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuariosAPI()
    {
        try {
            $sql = "SELECT DISTINCT historial_usuario_id, historial_usuario_nombre 
                    FROM macs_historial_act 
                    WHERE historial_situacion = 1
                    ORDER BY historial_usuario_nombre";
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

}
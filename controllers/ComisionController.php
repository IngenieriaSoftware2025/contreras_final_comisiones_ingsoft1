<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Comision;

class ComisionController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('comision/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['comision_titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_titulo']))));
            
            $cantidad_titulo = strlen($_POST['comision_titulo']);
            
            if ($cantidad_titulo < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Título debe de tener mas de 4 caracteres'
                ]);
                exit;
            }
            
            if ($cantidad_titulo > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Título no puede exceder los 250 caracteres'
                ]);
                exit;
            }
            
            $_POST['comision_descripcion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_descripcion']))));
            
            $cantidad_descripcion = strlen($_POST['comision_descripcion']);
            
            if ($cantidad_descripcion < 10) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Descripción debe de tener mas de 9 caracteres'
                ]);
                exit;
            }
            
            $_POST['comision_tipo'] = strtoupper(trim(htmlspecialchars($_POST['comision_tipo'])));
            
            if (!in_array($_POST['comision_tipo'], ['TRANSMISIONES', 'INFORMATICA'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Tipo de comisión debe ser TRANSMISIONES o INFORMATICA'
                ]);
                exit;
            }
            
            $_POST['comision_fecha_inicio'] = trim(htmlspecialchars($_POST['comision_fecha_inicio']));
            
            if (empty($_POST['comision_fecha_inicio'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una fecha de inicio'
                ]);
                exit;
            }
            
            $_POST['comision_duracion'] = filter_var($_POST['comision_duracion'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['comision_duracion'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La duración debe ser mayor a 0'
                ]);
                exit;
            }
            
            $_POST['comision_duracion_tipo'] = strtoupper(trim(htmlspecialchars($_POST['comision_duracion_tipo'])));
            
            if (!in_array($_POST['comision_duracion_tipo'], ['HORAS', 'DIAS'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Tipo de duración debe ser HORAS o DIAS'
                ]);
                exit;
            }
            
            $_POST['comision_ubicacion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_ubicacion']))));
            
            $cantidad_ubicacion = strlen($_POST['comision_ubicacion']);
            
            if ($cantidad_ubicacion < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ubicación debe de tener mas de 4 caracteres'
                ]);
                exit;
            }
            
            $_POST['comision_observaciones'] = trim(htmlspecialchars($_POST['comision_observaciones']));
            $_POST['comision_usuario_creo'] = filter_var($_POST['comision_usuario_creo'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['comision_usuario_creo'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar el usuario que crea la comisión'
                ]);
                exit;
            }
            
            if ($_POST['comision_duracion_tipo'] == 'HORAS') {
                $fecha_fin = date('Y-m-d H:i:s', strtotime($_POST['comision_fecha_inicio'] . ' +' . $_POST['comision_duracion'] . ' hours'));
            } else {
                $fecha_fin = date('Y-m-d', strtotime($_POST['comision_fecha_inicio'] . ' +' . $_POST['comision_duracion'] . ' days'));
            }
            
            $_POST['comision_fecha_fin'] = $fecha_fin;
            $_POST['comision_estado'] = 'PROGRAMADA';
            $_POST['comision_fecha_creacion'] = '';
            $_POST['comision_situacion'] = 1;
            
            $comision = new Comision($_POST);
            $resultado = $comision->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Comisión registrada correctamente',
                    'id' => $resultado['id']
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar la comisión',
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
            $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
            $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["c.comision_situacion = 1"];

            if ($tipo) {
                $condiciones[] = "c.comision_tipo = '{$tipo}'";
            }

            if ($estado) {
                $condiciones[] = "c.comision_estado = '{$estado}'";
            }

            if ($fecha_inicio) {
                $condiciones[] = "c.comision_fecha_inicio >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "c.comision_fecha_inicio <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT 
                        c.*,
                        u.usuario_nom1,
                        u.usuario_ape1
                    FROM macs_comision c 
                    INNER JOIN macs_usuario u ON c.comision_usuario_creo = u.usuario_id
                    WHERE $where 
                    ORDER BY c.comision_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Comisiones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las comisiones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['comision_id'];
        $_POST['comision_titulo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_titulo']))));

        $cantidad_titulo = strlen($_POST['comision_titulo']);

        if ($cantidad_titulo < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Título debe de tener mas de 4 caracteres'
            ]);
            return;
        }

        if ($cantidad_titulo > 250) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Título no puede exceder los 250 caracteres'
            ]);
            return;
        }

        $_POST['comision_descripcion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_descripcion']))));

        $cantidad_descripcion = strlen($_POST['comision_descripcion']);

        if ($cantidad_descripcion < 10) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Descripción debe de tener mas de 9 caracteres'
            ]);
            return;
        }

        $_POST['comision_tipo'] = strtoupper(trim(htmlspecialchars($_POST['comision_tipo'])));

        if (!in_array($_POST['comision_tipo'], ['TRANSMISIONES', 'INFORMATICA'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Tipo de comisión debe ser TRANSMISIONES o INFORMATICA'
            ]);
            return;
        }

        $_POST['comision_fecha_inicio'] = trim(htmlspecialchars($_POST['comision_fecha_inicio']));

        if (empty($_POST['comision_fecha_inicio'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una fecha de inicio'
            ]);
            return;
        }

        $_POST['comision_duracion'] = filter_var($_POST['comision_duracion'], FILTER_SANITIZE_NUMBER_INT);

        if ($_POST['comision_duracion'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La duración debe ser mayor a 0'
            ]);
            return;
        }

        $_POST['comision_duracion_tipo'] = strtoupper(trim(htmlspecialchars($_POST['comision_duracion_tipo'])));

        if (!in_array($_POST['comision_duracion_tipo'], ['HORAS', 'DIAS'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Tipo de duración debe ser HORAS o DIAS'
            ]);
            return;
        }

        $_POST['comision_ubicacion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['comision_ubicacion']))));

        $cantidad_ubicacion = strlen($_POST['comision_ubicacion']);

        if ($cantidad_ubicacion < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ubicación debe de tener mas de 4 caracteres'
            ]);
            return;
        }

        $_POST['comision_observaciones'] = trim(htmlspecialchars($_POST['comision_observaciones']));

        try {
            if ($_POST['comision_duracion_tipo'] == 'HORAS') {
                $fecha_fin = date('Y-m-d H:i:s', strtotime($_POST['comision_fecha_inicio'] . ' +' . $_POST['comision_duracion'] . ' hours'));
            } else {
                $fecha_fin = date('Y-m-d', strtotime($_POST['comision_fecha_inicio'] . ' +' . $_POST['comision_duracion'] . ' days'));
            }

            $data = Comision::find($id);
            $data->sincronizar([
                'comision_titulo' => $_POST['comision_titulo'],
                'comision_descripcion' => $_POST['comision_descripcion'],
                'comision_tipo' => $_POST['comision_tipo'],
                'comision_fecha_inicio' => $_POST['comision_fecha_inicio'],
                'comision_duracion' => $_POST['comision_duracion'],
                'comision_duracion_tipo' => $_POST['comision_duracion_tipo'],
                'comision_fecha_fin' => $fecha_fin,
                'comision_ubicacion' => $_POST['comision_ubicacion'],
                'comision_observaciones' => $_POST['comision_observaciones'],
                'comision_estado' => $_POST['comision_estado'] ?? 'PROGRAMADA',
                'comision_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información de la comisión ha sido modificada exitosamente'
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
            $ejecutar = Comision::EliminarComision($id);

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

    public static function cambiarEstadoAPI()
    {
        getHeadersApi();

        try {
            $id = filter_var($_POST['comision_id'], FILTER_SANITIZE_NUMBER_INT);
            $estado = strtoupper(trim(htmlspecialchars($_POST['comision_estado'])));

            if (!in_array($estado, ['PROGRAMADA', 'EN_CURSO', 'COMPLETADA', 'CANCELADA'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Estado no válido'
                ]);
                return;
            }

            $data = Comision::find($id);
            $data->sincronizar([
                'comision_estado' => $estado
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estado de la comisión actualizado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cambiar estado',
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
}
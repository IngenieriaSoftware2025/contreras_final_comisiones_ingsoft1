<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\ComisionPersonal;

class ComisionPersonalController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('comisionpersonal/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['comision_id'] = filter_var($_POST['comision_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['comision_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una comisión válida'
                ]);
                exit;
            }

            $_POST['usuario_id'] = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['usuario_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario válido'
                ]);
                exit;
            }

            $_POST['comision_personal_usuario_asigno'] = filter_var($_POST['comision_personal_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['comision_personal_usuario_asigno'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar quién asigna al personal'
                ]);
                exit;
            }

            $verificarComisionActiva = self::fetchArray("SELECT c.comision_id FROM macs_comision c 
                                                       INNER JOIN macs_comision_personal cp ON c.comision_id = cp.comision_id 
                                                       WHERE cp.usuario_id = {$_POST['usuario_id']} 
                                                       AND c.comision_estado IN ('PROGRAMADA', 'EN_CURSO') 
                                                       AND cp.comision_personal_situacion = 1 
                                                       AND c.comision_situacion = 1");

            if (count($verificarComisionActiva) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este usuario ya tiene una comisión activa asignada'
                ]);
                exit;
            }

            $verificarDuplicado = self::fetchArray("SELECT comision_personal_id FROM macs_comision_personal 
                                                   WHERE comision_id = {$_POST['comision_id']} 
                                                   AND usuario_id = {$_POST['usuario_id']} 
                                                   AND comision_personal_situacion = 1");

            if (count($verificarDuplicado) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este usuario ya está asignado a esta comisión'
                ]);
                exit;
            }

            $_POST['comision_personal_observaciones'] = trim(htmlspecialchars($_POST['comision_personal_observaciones']));
            $_POST['comision_personal_fecha_asignacion'] = '';
            $_POST['comision_personal_situacion'] = 1;
            
            $comisionPersonal = new ComisionPersonal($_POST);
            $resultado = $comisionPersonal->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Personal asignado a la comisión correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al asignar personal a la comisión',
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
            $comision_id = isset($_GET['comision_id']) ? $_GET['comision_id'] : null;
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;

            $condiciones = ["cp.comision_personal_situacion = 1"];

            if ($comision_id) {
                $condiciones[] = "cp.comision_id = {$comision_id}";
            }

            if ($usuario_id) {
                $condiciones[] = "cp.usuario_id = {$usuario_id}";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT 
                        cp.*,
                        u.usuario_nom1,
                        u.usuario_ape1,
                        c.comision_titulo,
                        c.comision_tipo,
                        c.comision_estado,
                        c.comision_fecha_inicio,
                        c.comision_fecha_fin,
                        ua.usuario_nom1 as asigno_nom1,
                        ua.usuario_ape1 as asigno_ape1
                    FROM macs_comision_personal cp 
                    INNER JOIN macs_usuario u ON cp.usuario_id = u.usuario_id
                    INNER JOIN macs_comision c ON cp.comision_id = c.comision_id 
                    INNER JOIN macs_usuario ua ON cp.comision_personal_usuario_asigno = ua.usuario_id
                    WHERE $where 
                    ORDER BY cp.comision_personal_fecha_asignacion DESC";
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

        $id = $_POST['comision_personal_id'];
        
        $_POST['comision_id'] = filter_var($_POST['comision_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['comision_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una comisión válida'
            ]);
            return;
        }

        $_POST['usuario_id'] = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['usuario_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario válido'
            ]);
            return;
        }

        $_POST['comision_personal_usuario_asigno'] = filter_var($_POST['comision_personal_usuario_asigno'], FILTER_SANITIZE_NUMBER_INT);
        
        if ($_POST['comision_personal_usuario_asigno'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe especificar quién asigna al personal'
            ]);
            return;
        }

        try {
            $verificarComisionActiva = self::fetchArray("SELECT c.comision_id FROM macs_comision c 
                                                       INNER JOIN macs_comision_personal cp ON c.comision_id = cp.comision_id 
                                                       WHERE cp.usuario_id = {$_POST['usuario_id']} 
                                                       AND c.comision_estado IN ('PROGRAMADA', 'EN_CURSO') 
                                                       AND cp.comision_personal_situacion = 1 
                                                       AND c.comision_situacion = 1
                                                       AND cp.comision_personal_id != {$id}");

            if (count($verificarComisionActiva) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este usuario ya tiene una comisión activa asignada'
                ]);
                return;
            }

            $verificarDuplicado = self::fetchArray("SELECT comision_personal_id FROM macs_comision_personal 
                                                   WHERE comision_id = {$_POST['comision_id']} 
                                                   AND usuario_id = {$_POST['usuario_id']} 
                                                   AND comision_personal_situacion = 1 
                                                   AND comision_personal_id != {$id}");

            if (count($verificarDuplicado) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este usuario ya está asignado a esta comisión'
                ]);
                return;
            }

            $_POST['comision_personal_observaciones'] = trim(htmlspecialchars($_POST['comision_personal_observaciones']));

            $data = ComisionPersonal::find($id);
            $data->sincronizar([
                'comision_id' => $_POST['comision_id'],
                'usuario_id' => $_POST['usuario_id'],
                'comision_personal_usuario_asigno' => $_POST['comision_personal_usuario_asigno'],
                'comision_personal_observaciones' => $_POST['comision_personal_observaciones'],
                'comision_personal_situacion' => 1
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
            $ejecutar = ComisionPersonal::EliminarComisionPersonal($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La asignación ha sido eliminada correctamente'
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

    public static function buscarComisionesAPI()
    {
        try {
            $sql = "SELECT comision_id, comision_titulo, comision_tipo, comision_estado 
                    FROM macs_comision 
                    WHERE comision_situacion = 1 
                    ORDER BY comision_titulo";
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

    public static function buscarUsuariosDisponiblesAPI()
    {
        try {
            $sql = "SELECT u.usuario_id, u.usuario_nom1, u.usuario_ape1 
                    FROM macs_usuario u 
                    WHERE u.usuario_situacion = 1 
                    AND u.usuario_id NOT IN (
                        SELECT cp.usuario_id 
                        FROM macs_comision_personal cp 
                        INNER JOIN macs_comision c ON cp.comision_id = c.comision_id 
                        WHERE c.comision_estado IN ('PROGRAMADA', 'EN_CURSO') 
                        AND cp.comision_personal_situacion = 1 
                        AND c.comision_situacion = 1
                    )
                    ORDER BY u.usuario_nom1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios disponibles obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios disponibles',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
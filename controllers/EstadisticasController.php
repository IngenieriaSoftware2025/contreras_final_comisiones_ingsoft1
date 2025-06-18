<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticasController extends ActiveRecord
{

   public static function renderizarPagina(Router $router)
   {
       session_start();
       if (!isset($_SESSION['usuario_id'])) {
           $app_name = $_ENV['APP_NAME'];
           header("Location: /$app_name/");
           exit;
       }
       
       $router->render('estadisticas/index', []);
   }

   public static function buscarComisionesPorTipoAPI(){
       try {
           $sql = "SELECT comision_tipo as tipo, COUNT(*) as cantidad 
                   FROM macs_comision 
                   WHERE comision_situacion = 1 
                   GROUP BY comision_tipo 
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comisiones por tipo obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las comisiones por tipo',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarComisionesPorEstadoAPI(){
       try {
           $sql = "SELECT comision_estado as estado, COUNT(*) as cantidad
                   FROM macs_comision 
                   WHERE comision_situacion = 1
                   GROUP BY comision_estado
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Estados de comisiones obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los estados',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarPersonalMasAsignadoAPI(){
       try {
           $sql = "SELECT u.usuario_nom1 || ' ' || u.usuario_ape1 as personal, COUNT(cp.comision_personal_id) as asignaciones
                   FROM macs_comision_personal cp
                   INNER JOIN macs_usuario u ON cp.usuario_id = u.usuario_id
                   WHERE cp.comision_personal_situacion = 1 AND u.usuario_situacion = 1
                   GROUP BY cp.usuario_id, u.usuario_nom1, u.usuario_ape1
                   ORDER BY asignaciones DESC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Personal más asignado obtenido correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener el personal',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarUsuariosCreadoresAPI(){
       try {
           $sql = "SELECT u.usuario_nom1 || ' ' || u.usuario_ape1 as creador, COUNT(c.comision_id) as comisiones_creadas
                   FROM macs_comision c
                   INNER JOIN macs_usuario u ON c.comision_usuario_creo = u.usuario_id
                   WHERE c.comision_situacion = 1 AND u.usuario_situacion = 1
                   GROUP BY c.comision_usuario_creo, u.usuario_nom1, u.usuario_ape1
                   ORDER BY comisiones_creadas DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Usuarios creadores obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los usuarios creadores',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarDuracionPromedioAPI(){
       try {
           $sql = "SELECT 
                       comision_duracion_tipo as tipo_duracion,
                       AVG(comision_duracion) as promedio,
                       COUNT(*) as total_comisiones
                   FROM macs_comision
                   WHERE comision_situacion = 1
                   GROUP BY comision_duracion_tipo
                   ORDER BY total_comisiones DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Duración promedio obtenida correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener la duración promedio',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarUbicacionesMasFrecuentesAPI(){
       try {
           $sql = "SELECT comision_ubicacion as ubicacion, COUNT(*) as cantidad
                   FROM macs_comision 
                   WHERE comision_situacion = 1
                   GROUP BY comision_ubicacion
                   ORDER BY cantidad DESC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Ubicaciones más frecuentes obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las ubicaciones',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarComisionesPorMesAPI(){
       try {
           $sql = "SELECT 
                       strftime('%Y-%m', comision_fecha_inicio) as mes,
                       COUNT(*) as cantidad,
                       comision_tipo
                   FROM macs_comision
                   WHERE comision_situacion = 1
                   GROUP BY strftime('%Y-%m', comision_fecha_inicio), comision_tipo
                   ORDER BY mes DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comisiones por mes obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las comisiones por mes',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarComisionesActivasAPI(){
       try {
           $sql = "SELECT 
                       c.comision_titulo as titulo,
                       c.comision_tipo as tipo,
                       c.comision_estado as estado,
                       COUNT(cp.usuario_id) as personal_asignado
                   FROM macs_comision c
                   LEFT JOIN macs_comision_personal cp ON c.comision_id = cp.comision_id AND cp.comision_personal_situacion = 1
                   WHERE c.comision_situacion = 1 
                   AND c.comision_estado IN ('PROGRAMADA', 'EN_CURSO')
                   GROUP BY c.comision_id, c.comision_titulo, c.comision_tipo, c.comision_estado
                   ORDER BY c.comision_fecha_inicio DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comisiones activas obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las comisiones activas',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarPersonalDisponibleAPI(){
       try {
           $sql = "SELECT 
                       COUNT(*) as total_usuarios,
                       COUNT(CASE WHEN disponible.usuario_id IS NULL THEN 1 END) as usuarios_disponibles,
                       COUNT(CASE WHEN disponible.usuario_id IS NOT NULL THEN 1 END) as usuarios_ocupados
                   FROM macs_usuario u
                   LEFT JOIN (
                       SELECT DISTINCT cp.usuario_id
                       FROM macs_comision_personal cp
                       INNER JOIN macs_comision c ON cp.comision_id = c.comision_id
                       WHERE c.comision_estado IN ('PROGRAMADA', 'EN_CURSO')
                       AND cp.comision_personal_situacion = 1
                       AND c.comision_situacion = 1
                   ) disponible ON u.usuario_id = disponible.usuario_id
                   WHERE u.usuario_situacion = 1";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Disponibilidad de personal obtenida correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener la disponibilidad de personal',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarResumenGeneralAPI(){
       try {
           $sql = "SELECT 
                       (SELECT COUNT(*) FROM macs_comision WHERE comision_situacion = 1) as total_comisiones,
                       (SELECT COUNT(*) FROM macs_comision WHERE comision_situacion = 1 AND comision_estado = 'PROGRAMADA') as comisiones_programadas,
                       (SELECT COUNT(*) FROM macs_comision WHERE comision_situacion = 1 AND comision_estado = 'EN_CURSO') as comisiones_en_curso,
                       (SELECT COUNT(*) FROM macs_comision WHERE comision_situacion = 1 AND comision_estado = 'COMPLETADA') as comisiones_completadas,
                       (SELECT COUNT(*) FROM macs_usuario WHERE usuario_situacion = 1) as total_usuarios,
                       (SELECT COUNT(*) FROM macs_brigada_ubicacion WHERE brigada_ubicacion_situacion = 1) as total_ubicaciones";
           $data = self::fetchFirst($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Resumen general obtenido correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener el resumen general',
               'detalle' => $e->getMessage()
           ]);
       }
   }
}
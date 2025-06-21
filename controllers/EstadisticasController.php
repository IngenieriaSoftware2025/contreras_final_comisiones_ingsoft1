<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticasController extends ActiveRecord
{

   public static function renderizarPagina(Router $router)
   {
       $router->render('estadisticas/index', []);
   }

   public static function buscarComisionesPorComandoAPI(){
       try {
           $sql = "SELECT comision_comando as comando, COUNT(*) as cantidad 
                   FROM macs_comision 
                   WHERE comision_situacion = 1 
                   GROUP BY comision_comando 
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comisiones por comando obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las comisiones por comando',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarComisionesPorUbicacionAPI(){
       try {
           $sql = "SELECT FIRST 10 comision_ubicacion as ubicacion, COUNT(*) as cantidad 
                   FROM macs_comision 
                   WHERE comision_situacion = 1 
                   GROUP BY comision_ubicacion 
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comisiones por ubicación obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las comisiones por ubicación',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarPersonalPorUnidadAPI(){
       try {
           $sql = "SELECT personal_unidad as unidad, COUNT(*) as cantidad 
                   FROM macs_personal_comisiones 
                   WHERE personal_situacion = 1 
                   GROUP BY personal_unidad 
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Personal por unidad obtenido correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener el personal por unidad',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarComisionesVsPersonalAPI(){
       try {
           $sql = "SELECT 
                       'Comisiones Activas' as categoria, 
                       COUNT(*) as cantidad 
                   FROM macs_comision 
                   WHERE comision_situacion = 1 
                   AND comision_estado IN ('PROGRAMADA', 'EN_CURSO')
                   UNION ALL
                   SELECT 
                       'Personal Disponible' as categoria, 
                       COUNT(*) as cantidad 
                   FROM macs_personal_comisiones 
                   WHERE personal_situacion = 1";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Comparación comisiones vs personal obtenida correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener la comparación comisiones vs personal',
               'detalle' => $e->getMessage()
           ]);
       }
   }
}
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
}
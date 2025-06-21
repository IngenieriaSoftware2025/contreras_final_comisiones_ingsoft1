<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class AppController extends ActiveRecord
{

    private static function obtenerPermisosUsuario($usuario_id) {
        $sql = "SELECT 
                    app.app_nombre_corto as modulo,
                    p.permiso_tipo as accion
                FROM macs_asig_permisos ap
                INNER JOIN macs_aplicacion app ON ap.asignacion_app_id = app.app_id
                INNER JOIN macs_permiso p ON ap.asignacion_permiso_id = p.permiso_id
                WHERE ap.asignacion_usuario_id = $usuario_id 
                AND ap.asignacion_situacion = 1
                ORDER BY app.app_nombre_corto";
        
        return self::fetchArray($sql);
    }

    public static function index(Router $router)
    {
        session_start();
        
        $permisosUsuario = [];
        if (isset($_SESSION['usuario_id']) && $_SESSION['rol'] !== 'administrador') {
            $permisosUsuario = self::obtenerPermisosUsuario($_SESSION['usuario_id']);
        }
        
        $router->render('pages/index', ['permisos_usuario' => $permisosUsuario]);
    }

    public static function verificarPermisosAPI() {
        session_start();
        
        if ($_SESSION['rol'] === 'administrador') {
            echo json_encode(['permitido' => true, 'rol' => 'administrador']);
            return;
        }
        
        $modulo = $_GET['modulo'] ?? '';
        $accion = $_GET['accion'] ?? 'LECTURA';
        $usuario_id = $_SESSION['usuario_id'];
        
        $sql = "SELECT COUNT(*) as tiene_permiso
                FROM macs_asig_permisos ap
                INNER JOIN macs_aplicacion app ON ap.asignacion_app_id = app.app_id
                INNER JOIN macs_permiso p ON ap.asignacion_permiso_id = p.permiso_id
                WHERE ap.asignacion_usuario_id = $usuario_id 
                AND UPPER(app.app_nombre_corto) = UPPER('$modulo')
                AND UPPER(p.permiso_tipo) = UPPER('$accion')
                AND ap.asignacion_situacion = 1";
        
        $resultado = self::fetchArray($sql);
        $permitido = $resultado[0]['tiene_permiso'] > 0;
        
        echo json_encode(['permitido' => $permitido, 'rol' => 'usuario']);
    }
}
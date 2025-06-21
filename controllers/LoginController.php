<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;
use Controllers\HistorialActController;

class LoginController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [], 'layouts/layoutlogin');
    }

    public static function login() {
        getHeadersApi();
        
        try {
            $dpi = htmlspecialchars($_POST['usuario_dpi']);
            $contrasena = htmlspecialchars($_POST['usuario_contra']);

            $queryExisteUser = "SELECT usuario_id, usuario_nom1, usuario_nom2, usuario_ape1, usuario_ape2, usuario_contra, usuario_rol FROM macs_usuario WHERE usuario_dpi = '$dpi' AND usuario_situacion = 1";

            $existeUsuario = ActiveRecord::fetchArray($queryExisteUser)[0];

            if ($existeUsuario) {
                $passDB = $existeUsuario['usuario_contra'];

                if (password_verify($contrasena, $passDB)) {
                    session_start();

                    $nombreCompleto = $existeUsuario['usuario_nom1'] . ' ' . $existeUsuario['usuario_nom2'] . ' ' . $existeUsuario['usuario_ape1'] . ' ' . $existeUsuario['usuario_ape2'];
                    $partesNombre = explode(' ', $nombreCompleto);
                    $nombreUser = $partesNombre[0] . ' ' . ($partesNombre[2] ?? '');
                    $usuarioId = $existeUsuario['usuario_id'];
                    
                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['dpi'] = $dpi;
                    $_SESSION['usuario_id'] = $usuarioId;
                    $_SESSION['rol'] = $existeUsuario['usuario_rol'];

                    HistorialActController::registrarActividad('LOGIN', 'INICIAR_SESION', 'Inició sesión en el sistema', 'login');

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario iniciado exitosamente',
                        'rol' => $existeUsuario['usuario_rol']
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta ingresar no existe',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar ingresar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function renderInicio(Router $router)
    {
        session_start();
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            header("Location: /contreras_final_comisiones_ingsoft1/");
            exit;
        }
        
        $router->render('pages/index', []);
    }

    public static function logout(){
        session_start();
        
        if (isset($_SESSION['usuario_id'])) {
            HistorialActController::registrarActividad('LOGIN', 'CERRAR_SESION', 'Cerró sesión del sistema', 'logout');
        }
        
        $_SESSION = [];
        session_destroy();
        header("Location: /contreras_final_comisiones_ingsoft1/");
        exit;
    }

}
<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

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

            $queryExisteUser = "SELECT usuario_id, usuario_nom1, usuario_ape1, usuario_contra FROM macs_usuario WHERE usuario_dpi = '$dpi' AND usuario_situacion = 1";

            $existeUsuario = ActiveRecord::fetchArray($queryExisteUser);

            if ($existeUsuario && count($existeUsuario) > 0) {
                $usuario = $existeUsuario[0];
                $passDB = $usuario['usuario_contra'];

                if ($contrasena === $passDB) {
                    session_start();

                    $nombreCompleto = $usuario['usuario_nom1'] . ' ' . $usuario['usuario_ape1'];
                    $usuarioId = $usuario['usuario_id'];
                    
                    $_SESSION['user'] = $nombreCompleto;
                    $_SESSION['dpi'] = $dpi;
                    $_SESSION['usuario_id'] = $usuarioId;
                    $_SESSION['usuario_rol'] = 'ADMIN';

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario iniciado exitosamente',
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
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['usuario_rol'])) {
            header("Location: /contreras_final_comisiones_ingsoft1/");
            exit;
        }
        
        $router->render('pages/index', []);
    }

    public static function logout(){
        session_start();
        
        $_SESSION = [];
        session_destroy();
        header("Location: /contreras_final_comisiones_ingsoft1/");
        exit;
    }

}
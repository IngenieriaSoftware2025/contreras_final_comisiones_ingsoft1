<?php

namespace Controllers;

use MVC\Router;

class AppController
{
    public static function index(Router $router)
    {
        session_start();
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['usuario_id'])) {
            header('Location: /contreras_final_comisiones_ingsoft1/');
            exit;
        }
        
        $router->render('pages/index', []);
    }
}
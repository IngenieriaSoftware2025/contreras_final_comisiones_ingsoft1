<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;
use Controllers\HistorialActController;

class UsuariosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nom1']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Primer nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nom2']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo nombre debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
            $cantidad_apellido = strlen($_POST['usuario_ape1']);
            
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Primer apellido debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            $cantidad_apellido = strlen($_POST['usuario_ape2']);
            
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Segundo apellido debe de tener mas de 1 caracteres'
                ]);
                exit;
            }
            
            $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['usuario_tel']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El telefono debe de tener 8 numeros'
                ]);
                exit;
            }
            
            $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
            
            $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
                ]);
                exit;
            }

            $verificarDpiExistente = self::fetchArray("SELECT usuario_id FROM macs_usuario WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_situacion = 1");

            if (count($verificarDpiExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario registrado con este DPI'
                ]);
                exit;
            }
            
            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electronico no es valido'
                ]);
                exit;
            }

            $verificarCorreoExistente = self::fetchArray("SELECT usuario_id FROM macs_usuario WHERE usuario_correo = '{$_POST['usuario_correo']}' AND usuario_situacion = 1");

            if (count($verificarCorreoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario registrado con este correo electrónico'
                ]);
                exit;
            }
            
            if (strlen($_POST['usuario_contra']) < 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
                ]);
                exit;
            }
            
            if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las contraseñas no coinciden'
                ]);
                exit;
            }

            $_POST['usuario_rol'] = trim(htmlspecialchars($_POST['usuario_rol']));
            
            if (empty($_POST['usuario_rol'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un rol'
                ]);
                exit;
            }
            
            if ($_POST['usuario_rol'] !== 'administrador' && $_POST['usuario_rol'] !== 'usuario') {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El rol debe ser administrador o usuario'
                ]);
                exit;
            }
            
            $_POST['usuario_token'] = uniqid();
            $dpi = $_POST['usuario_dpi'];
            
            if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['usuario_fotografia'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];
                
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          
                $allowed = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($fileExtension, $allowed)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'Solo puede cargar archivos JPG, PNG o JPEG',
                    ]);
                    exit;
                }
                
                if ($fileSize >= 2000000) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 2,
                        'mensaje' => 'La imagen debe pesar menos de 2MB',
                    ]);
                    exit;
                }
                
                if ($fileError === 0) {
                    $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
                    
                    $directorioFotos = __DIR__ . "/../../storage/fotosUsuarios/";
                    if (!file_exists($directorioFotos)) {
                        mkdir($directorioFotos, 0755, true);
                    }
                    
                    $subido = move_uploaded_file($file['tmp_name'], __DIR__ . "/../../" . $ruta);
                    
                    if ($subido) {
                        $_POST['usuario_fotografia'] = $ruta;
                    } else {
                        http_response_code(500);
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Error al subir la fotografia',
                        ]);
                        exit;
                    }
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Error en la carga de fotografia',
                    ]);
                    exit;
                }
            } else {
                $_POST['usuario_fotografia'] = '';
            }
            
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            $usuario = new Usuarios($_POST);
            $resultado = $usuario->crear();

            if($resultado['resultado'] == 1){
                $nombre_completo = $_POST['usuario_nom1'] . ' ' . $_POST['usuario_ape1'];
                
                HistorialActController::registrarActividad('USUARIOS', 'CREAR', 'Registró usuario: ' . $nombre_completo, 'usuarios/guardar');
                
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en registrar al usuario',
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
            $sql = "SELECT * FROM macs_usuario WHERE usuario_situacion = 1 ORDER BY usuario_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['usuario_id'];
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));

        $cantidad_nombre = strlen($_POST['usuario_nom1']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));

        $cantidad_nombre = strlen($_POST['usuario_nom2']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Segundo nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        $cantidad_apellido = strlen($_POST['usuario_ape1']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Primer apellido debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
        $cantidad_apellido = strlen($_POST['usuario_ape2']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Segundo apellido debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_VALIDATE_INT);

        if (strlen($_POST['usuario_tel']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe de tener 8 numeros'
            ]);
            return;
        }

        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
        $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));

        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos del DPI debe de ser igual a 13'
            ]);
            return;
        }

        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
            return;
        }

        $_POST['usuario_rol'] = trim(htmlspecialchars($_POST['usuario_rol']));
        
        if (empty($_POST['usuario_rol'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un rol'
            ]);
            return;
        }
        
        if ($_POST['usuario_rol'] !== 'administrador' && $_POST['usuario_rol'] !== 'usuario') {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El rol debe ser administrador o usuario'
            ]);
            return;
        }

        try {
            $verificarDpiExistente = self::fetchArray("SELECT usuario_id FROM macs_usuario WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_situacion = 1 AND usuario_id != {$id}");

            if (count($verificarDpiExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro usuario registrado con este DPI'
                ]);
                return;
            }

            $verificarCorreoExistente = self::fetchArray("SELECT usuario_id FROM macs_usuario WHERE usuario_correo = '{$_POST['usuario_correo']}' AND usuario_situacion = 1 AND usuario_id != {$id}");

            if (count($verificarCorreoExistente) > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro usuario registrado con este correo electrónico'
                ]);
                return;
            }

            $sql = "UPDATE macs_usuario SET 
                    usuario_nom1 = '{$_POST['usuario_nom1']}',
                    usuario_nom2 = '{$_POST['usuario_nom2']}',
                    usuario_ape1 = '{$_POST['usuario_ape1']}',
                    usuario_ape2 = '{$_POST['usuario_ape2']}',
                    usuario_tel = '{$_POST['usuario_tel']}',
                    usuario_direc = '{$_POST['usuario_direc']}',
                    usuario_dpi = '{$_POST['usuario_dpi']}',
                    usuario_correo = '{$_POST['usuario_correo']}',
                    usuario_rol = '{$_POST['usuario_rol']}'
                    WHERE usuario_id = {$id}";
            
            $resultado = self::SQL($sql);

            $nombre_completo = $_POST['usuario_nom1'] . ' ' . $_POST['usuario_ape1'];
            
            HistorialActController::registrarActividad('USUARIOS', 'ACTUALIZAR', 'Modificó usuario: ' . $nombre_completo, 'usuarios/modificar');

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La informacion del usuario ha sido modificada exitosamente'
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
            
            $sql_usuario = "SELECT usuario_nom1, usuario_ape1 FROM macs_usuario WHERE usuario_id = $id";
            $usuario_data = self::fetchFirst($sql_usuario);
            
            $ejecutar = Usuarios::EliminarUsuarios($id);

            if ($usuario_data) {
                $nombre_completo = $usuario_data['usuario_nom1'] . ' ' . $usuario_data['usuario_ape1'];
                
                HistorialActController::registrarActividad('USUARIOS', 'ELIMINAR', 'Eliminó usuario: ' . $nombre_completo, 'usuarios/eliminar');
            }

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
}
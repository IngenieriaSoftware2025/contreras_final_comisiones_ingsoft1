<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= asset('build/js/app.js') ?>"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Brigada de Comunicaciones</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark  bg-dark">
        
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/contreras_final_comisiones_ingsoft1/inicio">
                <img src="<?= asset('./images/cit.png') ?>" width="35px'" alt="cit" >
                MACS
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/contreras_final_comisiones_ingsoft1/inicio"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/contreras_final_comisiones_ingsoft1/comisionpersonal">
                            <i class="bi bi-person-plus-fill me-2"></i>Personal Comisiones
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/contreras_final_comisiones_ingsoft1/comisiones">
                            <i class="bi bi-clipboard-data-fill me-2"></i>Comisiones
                        </a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Gestión de Permisos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" id="dropdownPermisos" style="margin: 0;">
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/aplicacion"><i class="bi bi-app-indicator me-2"></i>Aplicaciones</a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/permisos"><i class="bi bi-shield-lock-fill me-2"></i>Permisos</a>
                            </li>
                            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/asignacionpermisos"><i class="bi bi-key-fill me-2"></i>Asignación Permisos</a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/usuarios"><i class="bi bi-person-plus me-2"></i>Crear Usuarios</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div> 

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Registros
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" id="dropdownRegistros" style="margin: 0;">
                            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/estadisticas"><i class="bi bi-bar-chart-fill me-2"></i>Estadísticas</a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/mapa"><i class="bi bi-map-fill me-2"></i>Mapa</a>
                            </li>
                            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/contreras_final_comisiones_ingsoft1/historial"><i class="bi bi-clock-history me-2"></i>Historial Actividad</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div> 

                </ul> 
                
                <?php 
                if(isset($_SESSION['user'])): 
                ?>
                    <div class="d-flex align-items-center me-3">
                        <span class="text-white me-3">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= $_SESSION['user'] ?> 
                            <?php if($_SESSION['rol'] === 'administrador'): ?>
                                <span class="badge bg-success ms-1">ADMIN</span>
                            <?php else: ?>
                                <span class="badge bg-primary ms-1">USUARIO</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-lg-2 d-grid mb-lg-0 mb-2">
                        <div class="d-flex gap-2">
                            <a href="/contreras_final_comisiones_ingsoft1/logout" class="btn btn-danger">
                                <i class="bi bi-box-arrow-right"></i>Salir
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                        <a href="/contreras_final_comisiones_ingsoft1/login" class="btn btn-danger">
                            <i class="bi bi-box-arrow-in-right"></i>Login
                        </a>
                    </div>
                <?php endif; ?>

            
            </div>
        </div>
        
    </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        
        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid " >
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>
</html>
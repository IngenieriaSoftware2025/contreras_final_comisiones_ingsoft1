<style>
    body {
        background: linear-gradient(135deg,rgb(44, 62, 80) 0%,rgb(52, 152, 219) 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .header {
        padding: 2rem;
        text-align: center;
        border-radius: 15px;
        margin-top: 2rem;
        margin-bottom: 2rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .logo {
        font-size: 3rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 1rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .container {
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-img {
      border-radius: 10px;
      width: 100%;
      height: 100%;
      max-height: 300px;
      object-fit: cover;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin-bottom: 1rem;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.2rem;
      text-align: center;
    }

    .product-img:hover {
      transform: scale(1.05) rotate(-2deg);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      cursor: pointer;
    }

    .user-welcome-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
</style>
<body>
    <div class="container">
        <?php if(isset($_SESSION['user'])): ?>
        <div class="row mb-4 justify-content-center">
            <div class="col-lg-8">
                <div class="card user-welcome-card border-0 rounded-4">
                    <div class="card-body text-center p-4">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-primary fw-bold"><?= $_SESSION['user'] ?></h4>
                                <?php if($_SESSION['rol'] === 'administrador'): ?>
                                    <span class="badge bg-success fs-6">ADMINISTRADOR</span>
                                    <p class="text-muted mt-2 mb-0">Tienes acceso completo a todos los módulos del sistema</p>
                                <?php else: ?>
                                    <span class="badge bg-primary fs-6">USUARIO</span>
                                    <?php if(isset($permisos_usuario) && !empty($permisos_usuario)): ?>
                                        <?php
                                            $modulos = array_unique(array_column($permisos_usuario, 'modulo'));
                                            $modulosTexto = implode(', ', $modulos);
                                            
                                            $permisosDetalle = [];
                                            foreach($permisos_usuario as $permiso) {
                                                $permisosDetalle[] = $permiso['accion'] . ' en ' . $permiso['modulo'];
                                            }
                                            $permisosTexto = implode(', ', $permisosDetalle);
                                        ?>
                                        <p class="text-muted mt-2 mb-1">
                                            <strong>Tiene acceso a:</strong> <?= $modulosTexto ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <strong>Permisos:</strong> <?= $permisosTexto ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-muted mt-2 mb-0">No tienes permisos asignados. Contacta al administrador.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="header">
            <div class="logo">¡Bienvenido al Sistema de Comisiones MACS!</div>
        </div>
        
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <p class="lead text-white">
                    "Gestiona comisiones de la Brigada de Comunicaciones de manera eficiente. Registra comisiones por tipo (Transmisiones/Informática), asigna personal, controla usuarios y permisos, mantén el historial de actividades y visualiza estadísticas en tiempo real."
                </p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12 text-center mb-4">
                <h2 class="text-uppercase fw-bold text-white">Módulos del Sistema</h2>
                <p class="text-light">Gestiona todos los aspectos de las comisiones desde una sola plataforma.</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-gratis/grupo-personas-trabajando-juntas_24877-51310.jpg" alt="Personal" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Personal Comisiones</h5>
                        <p class="card-text text-muted">Registra el personal que realizara comisiones.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/comisionpersonal" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-gratis/concepto-trabajo-equipo-negocios_1284-4006.jpg" alt="Comisiones" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Gestión de Comisiones</h5>
                        <p class="card-text text-muted">Registra comisiones de Transmisiones o Informática con duración y ubicación.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/comisiones" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/diseno-multimedia-color_1284-883.jpg?semt=ais_items_boosted&w=740" alt="aplicaciones" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Control de Aplicaciones</h5>
                        <p class="card-text text-muted">Gestiona las aplicaciones del sistema con nombres y configuraciones.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/aplicacion" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/desarrollo-aplicaciones-moviles_24908-58350.jpg?semt=ais_hybrid&w=740" alt="permisos" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Sistema de Permisos</h5>
                        <p class="card-text text-muted">Controla el acceso con mínimo 3 tipos de permisos de forma segura.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/permisos" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-premium/usuarios-grupo-personas-icono-perfil-usuario_24877-40756.jpg" alt="Gestión de Usuarios" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Gestión de Usuarios</h5>
                        <p class="card-text text-muted">Administra personal de la brigada con información completa y fotografías.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/usuarios" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://static.vecteezy.com/system/resources/previews/005/190/843/non_2x/acquiring-permits-concept-icon-obtaining-license-idea-thin-line-illustration-getting-approval-legal-documents-and-permissions-formal-application-isolated-outline-drawing-editable-stroke-vector.jpg" alt="permisos" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Asignación de Permisos</h5>
                        <p class="card-text text-muted">Asigna permisos específicos a usuarios para control de acceso.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/asignacionpermisos" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/concepto-historial-navegacion_23-2148207095.jpg" alt="Historial" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Historial de Actividades</h5>
                        <p class="card-text text-muted">Historial de actividades y reportes.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/historial" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-gratis/analisis-datos-graficos_24877-51215.jpg" alt="Estadísticas" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Estadísticas y Gráficas</h5>
                        <p class="card-text text-muted">Visualiza estadísticas de comisiones con gráficas y reportes.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/estadisticas" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                      <img src="https://img.freepik.com/vector-gratis/mapa-mundo-ubicacion-pin_24877-54109.jpg" alt="Mapa" style="max-width:100%; max-height:100%; border-radius:10px;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Mapa de la Brigada</h5>
                        <p class="card-text text-muted">Visualiza la ubicación de la Brigada de Comunicaciones en el mapa.</p>
                        <a href="/contreras_final_comisiones_ingsoft1/mapa" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6 mx-auto text-center">
                <a href="/contreras_final_comisiones_ingsoft1/comisiones" class="btn btn-light btn-lg mt-3">Comenzar a gestionar comisiones</a>
            </div>
        </div>
    </div>
    <script src="<?= asset('build/js/inicio.js') ?>"></script>
</body>
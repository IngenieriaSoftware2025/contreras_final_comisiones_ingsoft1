<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estadísticas - Brigada de Comunicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container-fluid py-4">

<div class="bg-primary text-white p-4 rounded-4 mb-4 text-center shadow">
    <h1 class="mb-3">📊 PANEL DE ESTADÍSTICAS</h1>
    <p class="mb-0">Análisis completo del sistema de comisiones de la Brigada de Comunicaciones</p>
</div>

<div class="mb-5">
    <h3 class="text-primary fw-bold text-center mb-4 position-relative">
        ANÁLISIS DE COMISIONES
        <div class="bg-primary mx-auto mt-2" style="width: 80px; height: 3px; border-radius: 2px;"></div>
    </h3>
    <div class="row justify-content-center">
        <div class="col-lg-8 mb-3">
            <div class="card shadow border-0 rounded-4 h-100" style="min-height: 450px;">
                <div class="card-header bg-light text-center border-0">
                    <h5 class="text-primary fw-bold mb-0">Comisiones por Tipo</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center" style="height: 320px;">
                        <canvas id="grafico1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>

</body>
</html>
<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">ASIGNACIÓN DE PERSONAL A COMISIONES</h3>
                    </div>
                    <form id="formComisionPersonal" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="comision_personal_id" name="comision_personal_id">
                        <input type="hidden" id="comision_personal_fecha_asignacion" name="comision_personal_fecha_asignacion" value="">
                        <input type="hidden" id="comision_personal_usuario_asigno" name="comision_personal_usuario_asigno" value="">
                        <input type="hidden" id="comision_personal_situacion" name="comision_personal_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="comision_id" class="form-label">Comisión</label>
                                <select class="form-control form-control-lg" id="comision_id" name="comision_id" required>
                                    <option value="">Seleccione una comisión</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_id" class="form-label">Personal</label>
                                <select class="form-control form-control-lg" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccione personal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="comision_personal_observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control form-control-lg" id="comision_personal_observaciones" name="comision_personal_observaciones" rows="3" placeholder="Ingrese observaciones sobre la asignación"></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Asignar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarAsignaciones">
                                <i class="bi bi-search me-2"></i>Buscar Asignaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Asignaciones de personal registradas en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableComisionPersonal" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Comisión</th>
                                    <th>Tipo</th>
                                    <th>Personal Asignado</th>
                                    <th>Fecha Asignación</th>
                                    <th>Estado Comisión</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Asignado por</th>
                                    <th>Situación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/comisionpersonal/index.js') ?>"></script>
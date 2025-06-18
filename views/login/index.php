<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <form id="FormLogin">
              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">MACS</h2>
                <p class="text-white-50 mb-5">Sistema de Gestión de Comisiones</p>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="text" name="usuario_dpi" id="usuario_dpi" class="form-control form-control-lg" />
                  <label class="form-label" for="usuario_dpi">DPI</label>
                </div>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="password" name="usuario_contra" id="usuario_contra" class="form-control form-control-lg" />
                  <label class="form-label" for="usuario_contra">Contraseña</label>
                </div>

                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">¿Olvidaste tu contraseña?</a></p>

                <button type="submit" id="BtnIniciar" class="btn btn-danger btn-lg px-5">Iniciar Sesión</button>

                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                  <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                  <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                  <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                </div>

              </div>

              <div>
                <p class="mb-0">¿No tienes cuenta? <a href="#!" class="text-white-50 fw-bold">Contacta al administrador</a>
                </p>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= asset('build/js/login/index.js') ?>"></script>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: #2c3e50;
        background: -webkit-linear-gradient(to right, rgba(44, 62, 80, 1), rgba(52, 152, 219, 1));
        background: linear-gradient(to right, rgba(44, 62, 80, 1), rgba(52, 152, 219, 1));
    }
    body {
        min-height: 100vh;
        height: 100%;
    }
    .gradient-custom {
        min-height: 100vh;
        height: 100%;
    }
</style>
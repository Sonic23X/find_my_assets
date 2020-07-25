<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">

  	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Find my assets</title>

    <link rel="icon" href="./favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


    <!-- Custom CSS -->
    <link rel="stylesheet" href="./resources/css/register.css">

  </head>
  <body class="hidden">

    <!-- loader -->
    <div class="loader">
      <div class="loadingio-spinner-spinner-65dox1ras4p">
        <div class="ldio-jsxn2qe688r">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    </div>

    <nav class="navbar fixed-top navbar-light bg-light">
      <a class="navbar-brand" href="<?= base_url( ) ?>">
        <i class="fas fa-arrow-left"></i>
      </a>
    </nav>

    <!-- Register Form -->
    <div class="container">
      <div class="register-box">

        <form id="registro" action="<?= base_url( '/nuevo' ) ?>">
  				<p class="text-center py-3"><b>Completa tus datos</b></p>

          <div class="row">
            <div class="col-sm">
              <input type="text" class="form-control" placeholder="Nombre" id="nombre">
            </div>
            <div class="col-sm">
              <input type="text" class="form-control" placeholder="Apellidos" id="apellidos">
            </div>
          </div>

  				<div class="row mt-3">
  					<div class="col-sm">
  						<input type="email" class="form-control" placeholder="Correo Electrónico" id="email">
  					</div>
  					<div class="col-sm">
              <div class="form-group">
                <div class="input-group">
                  <input type="password" class="form-control" id="password" placeholder="Contraseña">
                  <div class="input-group-prepend">
                    <div class="input-group-text" id="icon">
                      <i class="fas fa-eye"></i>
                    </div>
                  </div>
                </div>
    		      </div>
  					</div>
  				</div>

  				<div class="row mt-4">
  					<div class="col-sm-4">
              <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
  					</div>
  					<div class="col-sm-8">
  						<div class="form-check-inline text-right">
  							<input type="checkbox" id="politicas" class="form-check-input" checked style="display: none;" >
    							<label class="form-check-label terminos-link" for="politicas">
  								Al registrarme, declaro qué acepto <a href="#" class="text-decoration-none"> los términos, condiones y políticas de provacidad </a> de Find my assets.
  							</label>
  						</div>
  					</div>
  				</div>

          <div class="row mt-3">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
              <p class="text-center">
                ¿Ya tienes una cuenta? <a class="btn btn-outline-primary btn-sm" href="<?= base_url( '/ingreso' ) ?>">Ingresar</a>
              </p>
            </div>
            <div class="col-sm-4"></div>
          </div>
          <input type="hidden" id="home" value="<?= base_url( ) ?>">
  	    </form>

      </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small">

      <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <span> Find my Assets </span>
      </div>

    </footer>

    <!-- jQuery library -->
    <script src="./resources/plugins/jquery/jquery-3.5.1.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Font Awesome 5.13.1 -->
    <script src="./resources/plugins/fontawesome/js/all.min.js"></script>

    <!-- Custom JS -->
    <script src="./resources/js/register.js"></script>

  </body>
</html>
